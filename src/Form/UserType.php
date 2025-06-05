<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('unitName', ChoiceType::class, [
                'label' => 'Đơn vị:',
                'choices' => [
                    'Hệ thống thông tin 2022' => 'Hệ thống thông tin 2022',
                    'Công nghệ thông tin 2022' => 'Công nghệ thông tin 2022',
                    'Khoa học máy tính 2022' => 'Khoa học máy tính 2022',
                    'Kỹ thuật phần mềm 2022' => 'Kỹ thuật phần mềm 2022',
                    'Khoa học dữ liệu 2022' => 'Khoa học dữ liệu 2022',
                ],
                'placeholder' => 'Chọn đơn vị',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fullName', TextType::class, [
                'label' => 'Họ và Tên',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Ngày sinh',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Nam' => 'Nam',
                    'Nữ' => 'Nữ',
                ],
                'label' => 'Giới tính',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'placeholder' => 'Chọn giới tính',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'me-2 fw-bold'],
            ])
            ->add('ethnicity', TextType::class, [
                'label' => 'Dân tộc',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('religion', TextType::class, [
                'label' => 'Tôn giáo',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('citizenId', TextType::class, [
                'label' => 'CMND/CCCD',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('issueDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Ngày cấp',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('placeOfIssue', TextType::class, [
                'label' => 'Nơi cấp',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('hometownAddress', TextType::class, [
                'label' => 'Quê quán',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('permanentAddress', TextType::class, [
                'label' => 'Thường trú',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('regisNumber', TextType::class, [
                'label' => 'Số nghị quyết chuẩn y kết nạp đoàn',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('joinPlace', TextType::class, [
                'label' => 'Nơi vào đoàn',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('cardPlace', TextType::class, [
                'label' => 'Nơi cấp thẻ đoàn',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('joinDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Ngày vào đoàn',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('unionRole', ChoiceType::class, [
                'label' => 'Chức vụ trong chi đoàn',
                'choices' => [
                    'Đoàn viên' => 'Đoàn viên',
                    'Bí thư chi đoàn' => 'Bí thư chi đoàn',
                    'Phó Bí thư chi đoàn' => 'Phó Bí thư chi đoàn',
                    'Ủy viên Ban Chấp hành chi đoàn' => 'Ủy viên Ban Chấp hành chi đoàn',
                    'Bí thư chi đoàn cơ sở' => 'Bí thư đoàn cơ sở',
                    'Phó bí thư chi đoàn cơ sở' => 'Phó bí thư chi đoàn cơ sở',
                    'Ủy viên Ban Chấp hành chi đoàn cơ sở' => 'Ủy viên ban chấp hành chi đoàn cơ sở',
                    'Ủy viên Ban Thường vụ chi đoàn cơ sở' => 'Ủy viên ban thường vụ chi đoàn cơ sở',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn chức vụ trong chi đoàn',
            ])
            ->add('association', ChoiceType::class, [
                'label' => 'Hiệp hội',
                'choices' => [
                    'Hội LHTN Việt Nam' => 'Hội LHTN Việt Nam',
                    'Hội Sinh viên Việt Nam' => 'Hội Sinh viên Việt Nam',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn hiệp hội',
                'required' => false,
            ])
            ->add('honorMember', ChoiceType::class, [
                'choices' => [
                    'Có' => 'Có',
                    'Không' => 'Không',
                ],
                'label' => 'Đoàn viên danh dự',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'placeholder' => 'Chọn đoàn viên danh dự',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'me-2 fw-bold'],
            ])
            ->add('joinPartyDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Ngày vào Đảng',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('partyPosition', TextType::class, [
                'label' => 'Chức vụ trong Đảng',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('salaryStatus', CheckboxType::class, [
                'label' => 'Có hưởng lương',
                'required' => false,
            ])
            ->add('unionBookNumber', CheckboxType::class, [
                'label' => 'Sổ đoàn viên',
                'required' => false,
            ])
            ->add('eduLevel', ChoiceType::class, [
                'label' => 'Trình độ văn hóa',
                'choices' => [
                    'Hệ 12/12' => 'Hệ 12/12',
                    'Tiểu học' => 'Tiểu học',
                    'Trung học cơ sở' => 'Trung học cơ sở',
                    'Trung học phổ thông 10/10' => 'Trung học phổ thông 10/10',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn trình độ văn hóa',
                'required' => false,
            ])
            ->add('proLevel', ChoiceType::class, [
                'label' => 'Trình độ chuyên môn',
                'choices' => [
                    'Sơ cấp' => 'Sơ cấp',
                    'Trung cấp chuyên nghiệp' => 'Trung cấp chuyên nghiệp',
                    'Cao đẳng' => 'Cao đẳng',
                    'Cử nhân' => 'Cử nhân',
                    'Thạc sĩ' => 'Thạc sĩ',
                    'Tiến sĩ' => 'Tiến sĩ',
                    'Chưa có' => 'Chưa có',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn trình độ chuyên môn',
                'required' => false,
            ])
            ->add('polTheory', ChoiceType::class, [
                'label' => 'Lý luận chính trị',
                'choices' => [
                    'Sơ cấp' => 'Sơ cấp',
                    'Trung cấp' => 'Trung cấp',
                    'Cao cấp' => 'Cao cấp',
                    'Cử nhân' => 'Cử nhân',
                    'Chưa có' => 'Chưa có',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn trình độ lý luận chính trị',
                'required' => false,
            ])
            ->add('itLevel', ChoiceType::class, [
                'label' => 'Trình độ tin học',
                'choices' => [
                    'Chuẩn kỹ năng sử dụng CNTT cơ bản' => 'Chuẩn kỹ năng sử dụng CNTT cơ bản',
                    'Chuẩn kỹ năng sử dụng CNTT nâng cao' => 'Chuẩn kỹ năng sử dụng CNTT nâng cao',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn trình độ tin học',
                'required' => false,
            ])
            ->add('langLevel', ChoiceType::class, [
                'label' => 'Ngoại ngữ',
                'choices' => [
                    'Bậc 1' => 'Bậc 1',
                    'Bậc 2' => 'Bậc 2',
                    'Bậc 3' => 'Bậc 3',
                    'Bậc 4' => 'Bậc 4',
                    'Bậc 5' => 'Bậc 5',
                    'Bậc 6' => 'Bậc 6',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn bậc ngoại ngữ',
                'required' => false,
            ])
            ->add('job', ChoiceType::class, [
                'choices' => [
                    'Sinh viên' => 'Sinh viên',
                    'Giảng viên/ Giáo viên' => 'Giảng viên',
                    'Học sinh' => 'Học sinh',
                    'Chưa có việc làm' => 'Chưa có việc làm',
                    'Khác' => 'Khác',
                ],
                'placeholder' => 'Chọn nghề nghiệp',
                'label' => 'Nghề nghiệp hiện nay',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Số điện thoại',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

