<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Account;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        /** @var Account $account */
        $account = $this->getUser();
        $unitName = $account->getFullName();

        $unitNameFromQuery = $request->query->get('unitName');

        if (in_array('ROLE_ADMIN', $account->getRoles(), true) && $unitNameFromQuery) {
            $users = $userRepository->findByUnitName($unitNameFromQuery);
        } else {
            $users = $userRepository->findByUnitName($unitName);
        }
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'unitName' => $unitNameFromQuery ?? $unitName,
        ]);
    }


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = new User();
        $user->setRole('ROLE_USER');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingUser = $userRepository->findOneBy(['fullName' => $user->getFullName()]);
            if ($existingUser) {
                $this->addFlash('error', 'Đoàn viên "' . $user->getFullName() . '" đã tồn tại!');
                return $this->redirectToRoute('app_user_new');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Thêm đoàn viên thành công!');
            return $this->render('user/new.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Cập nhật thông tin đoàn viên thành công!');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/export/{unitName}', name: 'export_users_excel', defaults: ['unitName' => null])]
    public function exportUsersExcel(UserRepository $userRepository, ?string $unitName): Response
    {
        if ($unitName) {
            $users = $userRepository->findByUnitName($unitName);
        } else {
            $users = $userRepository->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách người dùng');

        $columns = [
            'A' => [
                'title' => 'STT',
                'getter' => function ($user, $index) {
                    return $index + 1;
                }
            ],
            'B' => [
                'title' => 'Họ tên',
                'getter' => function ($user) {
                    return $user->getFullName();
                }
            ],
            'C' => [
                'title' => 'Ngày sinh',
                'getter' => function ($user) {
                    return $user->getDateOfBirth()?->format('d/m/Y');
                }
            ],
            'D' => [
                'title' => 'Giới tính',
                'getter' => function ($user) {
                    return $user->getGender();
                }
            ],
            'E' => [
                'title' => 'Dân tộc',
                'getter' => function ($user) {
                    return $user->getEthnicity();
                }
            ],
            'F' => [
                'title' => 'Tôn giáo',
                'getter' => function ($user) {
                    return $user->getReligion();
                }
            ],
            'G' => [
                'title' => 'CCCD/CMND',
                'getter' => function ($user) {
                    return $user->getCitizenId();
                }
            ],
            'H' => [
                'title' => 'Ngày cấp',
                'getter' => function ($user) {
                    return $user->getIssueDate()?->format('d/m/Y');
                }
            ],
            'I' => [
                'title' => 'Nơi cấp',
                'getter' => function ($user) {
                    return $user->getPlaceOfIssue();
                }
            ],
            'J' => [
                'title' => 'Quê quán',
                'getter' => function ($user) {
                    return $user->getHometownAddress();
                }
            ],
            'K' => [
                'title' => 'Địa chỉ thường trú',
                'getter' => function ($user) {
                    return $user->getPermanentAddress();
                }
            ],
            'L' => [
                'title' => 'Số đăng ký',
                'getter' => function ($user) {
                    return $user->getRegisNumber();
                }
            ],
            'M' => [
                'title' => 'Nơi kết nạp',
                'getter' => function ($user) {
                    return $user->getJoinPlace();
                }
            ],
            'N' => [
                'title' => 'Nơi cấp thẻ',
                'getter' => function ($user) {
                    return $user->getCardPlace();
                }
            ],
            'O' => [
                'title' => 'Ngày kết nạp',
                'getter' => function ($user) {
                    return $user->getJoinDate()?->format('d/m/Y');
                }
            ],
            'P' => [
                'title' => 'Chức vụ đoàn',
                'getter' => function ($user) {
                    return $user->getUnionRole();
                }
            ],
            'Q' => [
                'title' => 'Hiệp hội',
                'getter' => function ($user) {
                    return $user->getAssociation();
                }
            ],
            'R' => [
                'title' => 'Đoàn viên danh dự',
                'getter' => function ($user) {
                    return $user->getHonorMember();
                }
            ],
            'S' => [
                'title' => 'Ngày vào đảng',
                'getter' => function ($user) {
                    return $user->getJoinPartyDate()?->format('d/m/Y');
                }
            ],
            'T' => [
                'title' => 'Chức vụ đảng',
                'getter' => function ($user) {
                    return $user->getPartyPosition();
                }
            ],
            'U' => [
                'title' => 'Tình trạng lương',
                'getter' => function ($user) {
                    return $user->getSalaryStatus() ? 'Có' : 'Không';
                }
            ],
            'V' => [
                'title' => 'Số sổ đoàn',
                'getter' => function ($user) {
                    return $user->getUnionBookNumber() ? 'Có' : 'Không';
                }
            ],
            'W' => [
                'title' => 'Trình độ học vấn',
                'getter' => function ($user) {
                    return $user->getEduLevel();
                }
            ],
            'X' => [
                'title' => 'Trình độ chuyên môn',
                'getter' => function ($user) {
                    return $user->getProLevel();
                }
            ],
            'Y' => [
                'title' => 'Lý luận chính trị',
                'getter' => function ($user) {
                    return $user->getPolTheory();
                }
            ],
            'Z' => [
                'title' => 'Trình độ tin học',
                'getter' => function ($user) {
                    return $user->getItLevel();
                }
            ],
            'AA' => [
                'title' => 'Trình độ ngoại ngữ',
                'getter' => function ($user) {
                    return $user->getLangLevel();
                }
            ],
            'AB' => [
                'title' => 'Nghề nghiệp',
                'getter' => function ($user) {
                    return $user->getJob();
                }
            ],
            'AC' => [
                'title' => 'Số điện thoại',
                'getter' => function ($user) {
                    return $user->getPhoneNumber();
                }
            ],
            'AD' => [
                'title' => 'Email',
                'getter' => function ($user) {
                    return $user->getEmail();
                }
            ],
            'AE' => [
                'title' => 'Đơn vị',
                'getter' => function ($user) {
                    return $user->getUnitName();
                }
            ],
        ];

        foreach ($columns as $col => $config) {
            $sheet->setCellValue($col . '1', $config['title']);
        }

        $row = 2;
        foreach ($users as $index => $user) {
            foreach ($columns as $col => $config) {
                $value = $config['getter']($user, $index);
                $sheet->setCellValue($col . $row, $value);
            }
            $row++;
        }

        foreach (array_keys($columns) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $filename = 'danh_sach_doan_vien_' . ($unitName ?? 'tat_ca') . '.xlsx';

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=\"$filename\"");
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
