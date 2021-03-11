<?php


namespace App\Admin;


use App\Entity\Driver;
use App\Entity\License;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Sonata\AdminBundle\Datagrid\DatagridInterface;

class LicenseAdmin extends BaseAdmin
{

    protected $datagridValues = [
        '_sort_order'=>'DESC',
        '_sort_by'=>'issuedDate'
    ];

    protected function configureBatchActions($actions)
    {
        $actions['print'] = [
            'label' => 'Print',
            'ask_confirmation' => false
        ];
        return $actions;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('print', $this->getRouterIdParameter() . '/print');
        $collection->add('renew', $this->getRouterIdParameter() . '/renew');
    }

    public function createQuery($context = 'list')
    {
        $q = parent::createQuery();
        $alias = $q->getRootAliases()[0];
        $q->andWhere("{$alias}.published = 1");
        return $q;
    }


    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('driver')
            ->add('issuedDate')
            ->add('expiryDate')
            ->add('licenseGrade')
            ->add('licenseType')
            ->add('licenseNumber')
            ->add('status')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'print' => ['template' => 'list_button.html.twig', 'route' => 'print', 'label' => 'Print', 'icon' => 'fa fa-print'],
                    'renew' => ['template' => 'list_button.html.twig', 'route' => 'renew', 'label' => 'Renew', 'icon' => 'fa fa-refresh'],
                ]
            ]);
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('licenseType', ModelType::class, [
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Enter a license type',
                'btn_add' => false,
            ])
            ->add('licenseGrade', ModelType::class, [
                'required' => true,
                'label' => 'Grade',
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Enter a grade',
                'btn_add' => false,
            ])
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                'placeholder' => 'Choose a driver'
            ])
            ->add('issuedDate', DatePickerType::class, [
                'format' => "yyyy-MM-dd",
            ])
            ->add('fiaMedStdDate', DatePickerType::class, [
                'label' => 'FIA Med. Std.',
                'format' => "yyyy-MM-dd",
            ])
            ->add('correctedEyesight', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true,
                ],
            ])
            ->add('medSupervision', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true,
                ],
            ])
            ->add('wadb', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
            ->add('sequenceNumber', NumberType::class, [
                'required' => false,
            ]);

    }

    public function preValidate($object)
    {
        $this->createLicenseNumber($object);
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        if ($this->getRequest()->attributes->get('_route') !== 'admin_app_license_renew') {
            $endOfYear = new \DateTime("last day of december {$object->getIssuedDate()->format('Y')}");
            $object->setExpiryDate($endOfYear);
        }

        $gradeType = $object->getLicenseGrade()->getGradeType();
        $config = $gradeType->getConfig();
        $gradeTypeName = $gradeType->getName();
        if ($gradeTypeName !== 'driver' && $object->getLicenseType()) {
            return $errorElement->addViolation("You can't have a license type for a grade type different than driver!");
        } elseif ($gradeTypeName === 'driver' && !$object->getLicenseType()) {
            return $errorElement->addViolation("You must have a license type for a driver grade type!");
        }
        if ($object->getSequenceNumber() > $config['endSequence'] || $object->getSequenceNumber() < $config['startSequence']) {
            return $errorElement->addViolation("Sequence number should be between {$config['startSequence']} and {$config['endSequence']}");
        }
        if (substr((string)$object->getSequenceNumber(), -2, 2) == 13) {
            return $errorElement->addViolation("Sequence number cannot end with the number 13");
        }
        $em = $this->modelManager->getEntityManager(License::class);
        if ($object->getSequenceNumber()) {
            if ($this->getSubject()->getId()) {
                $licenses = $em->getRepository(License::class)->findLicenseExists($object->getSequenceNumber(), $object->getExpiryDate(), $object->getId());
            } else {
                $licenses = $em->getRepository(License::class)->findLicenseExists($object->getSequenceNumber(), $object->getExpiryDate());
            }
            if (count($licenses) > 0)
                return $errorElement->addViolation("Sequence number already exist!");

        }
        $oldLicense = $em->getUnitOfWork()->getOriginalEntityData($object);
        if (!$this->getSubject()->getId() || $oldLicense['license_grade_id'] != $object->getLicenseGrade()->getId()) {
            $license = $em->getRepository(License::class)->findOneBy(['driver' => $object->getDriver(), 'licenseGrade' => $object->getLicenseGrade(), 'expiryDate' => $object->getExpiryDate()]);
            if ($license)
                return $errorElement->addViolation("The Driver already have a license with this grade for this year!");
        }

    }

    public function createLicenseNumber($object, $date = null)
    {
        // ATCL-NAT-SEQNumber-022
        $baseName = "ATCL";
        $gradeTypeName = $object->getLicenseGrade()->getGradeType()->getName();
        $secondPart = $gradeTypeName === 'driver' ? $object->getLicenseType()->getName() : $object->getLicenseGrade()->getGradeLetter();
        $sequenceNumber = $object->getSequenceNumber() ?? $this->createSequenceNumber($object, $date);
        $year = substr($object->getIssuedDate()->format('Y'), -3);
        $formattedSequenceNumber = str_pad($sequenceNumber, 3, "0", STR_PAD_LEFT);  // sequence = 1 => formatted sequence = 001
        $licenseNumber = "{$baseName}-{$secondPart}-{$formattedSequenceNumber}-{$year}";
        $object->setLicenseNumber($licenseNumber);
        return $licenseNumber;

    }

    public function createSequenceNumber($object, $date = null)
    {
        // if 1 2 3 6 sequences in the database, the following code set the next sequence 4 not 7
        $gradeType = $object->getLicenseGrade()->getGradeType();
        $gradeTypeName = $gradeType->getName();
        $em = $this->modelManager->getEntityManager(License::class);
        $licenses = $em->getRepository(License::class)->findLicensesByGradeType($gradeTypeName, $date);
        $config = $gradeType->getConfig();
        $sequenceToBeSet = isset($config['startSequence']) ? $config['startSequence'] : 1;
        foreach ($licenses as $license) {
            $sequenceNumber = $license->getSequenceNumber();
            if (substr((string)$sequenceToBeSet, -2, 2) == 13){
                $sequenceToBeSet++;
            }
            if ($sequenceNumber == $sequenceToBeSet) {
                $sequenceToBeSet++;

                continue;
            }
            break;

        }
//        if (substr((string)$sequenceToBeSet, -2, 2) == 13) {
////            $sequenceToBeSet++;
//            foreach ($licenses as $license) {
//                $sequenceNumber = $license->getSequenceNumber();
//                if ($sequenceNumber == $sequenceToBeSet || substr((string)$sequenceToBeSet, -2, 2) == 13) {
//                    $sequenceToBeSet++;
//
//                    continue;
//                }
//                break;
//
//            }
//        }
        $object->setSequenceNumber($sequenceToBeSet);
        return $sequenceToBeSet;
    }

    public function prePersist($object)
    {
//        if ($this->getRequest()->attributes->get('_route') !== 'admin_app_license_renew') {
//            $endOfYear = new \DateTime('last day of December this year');
//            $object->setExpiryDate($endOfYear);
//        }
//        if (!$object->getSequenceNumber())
//            $this->createLicenseNumber($object);
    }

    public function getNewInstance()
    {
        $license = parent::getNewInstance();
//        $this->createSequenceNumber($license);
        $license->setIssuedDate(new \DateTime('NOW'));
        $license->setFiaMedStdDate(new \DateTime('NOW'));
        return $license;
    }
}