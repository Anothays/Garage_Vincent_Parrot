<?php

namespace App\Form;

use App\Entity\Image;
use App\Validator\Constraints\EasyAdminFile;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filename', FileUploadType::class, [
                'upload_dir' => 'public/media',
                'label' => 'fichier',
                'constraints' => [
                    new EasyAdminFile([
                        'mimeTypes' => [ // We want to let upload only jpeg or png
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le fichier doit être au format jpeg ou png.',
                    ]),
                    new Callback([$this, 'validateAltWhenFilenameNotEmpty']),
                ]
            ])


            ->add('alt', TextType::class, [
                'label' => 'texte alternatif',
                'help' => 'Décrivez votre image en une phrase simple et courte',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }

    public function validateAltWhenFilenameNotEmpty($filename, ExecutionContextInterface $context)
    {
        $file = $context->getObject()->getParent()->getNormData();
        $data = [...$context->getRoot()->getData()->getImages()][0];
        if (empty($file) && !empty($data->getAlt())) {
            $context
                ->buildViolation('Vous devez sélectionner une image')
                ->addViolation();
        }
    }
}
