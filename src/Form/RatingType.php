use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', NumberType::class, [
                'scale' => 1,
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.5
                ]
            ])
            ->add('comment', TextareaType::class, [
                'required' => false
            ]);
    }
}
