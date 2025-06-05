<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ExportController extends AbstractController
{
    #[Route('/export/users', name: 'export_users_excel')]
    public function exportUsers(UserRepository $userRepository): StreamedResponse
    {
        $users = $userRepository->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            'Đơn vị',
            'Họ và tên',
            'Ngày sinh',
            'Giới tính',
            'Dân tộc',
            'Tôn giáo',
            'CMND/CMND',
            'Ngày cấp',
            'Nơi cấp',
            'Quê quán',
            'Thường trú',
            'Số nghị quyết chuẩn y kết nạp đoàn',
            'Nơi vào đoàn',
            'Nơi cấp thẻ đoàn',
            'Ngày vào đoàn',
            'Chức vụ trong chi Đoàn',
            'Hiệp hội',
            'Đoàn viên danh dự',
            'Ngày vào Đảng',
            'Thời gian vào Đảng',
            'Có hưởng lương',
            'Sổ đoàn viên',
            'Trình độ văn hóa',
            'Trình độ chuyên môn',
            'Lý luận chính trị',
            'Trình độ tin học',
            'Ngoại ngữ',
            'Nghề nghiệp hiện nay',
            'Số điện thoại',
            'Email',
        ], null, 'A1');

        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue("A{$row}", $user->getUnitName());
            $sheet->setCellValue("B{$row}", $user->getFullName());
            $sheet->setCellValue("C{$row}", $user->getDateOfBirth() ? $user->getDateOfBirth()->format('d/m/Y') : '');
            $sheet->setCellValue("D{$row}", $user->getGender());
            $sheet->setCellValue("E{$row}", $user->getEthnicity());
            $sheet->setCellValue("F{$row}", $user->getReligion());
            $sheet->setCellValue("G{$row}", $user->getCitizenId());
            $sheet->setCellValue("H{$row}", $user->getIssueDate() ? $user->getIssueDate()->format('d/m/Y') : '');
            $sheet->setCellValue("I{$row}", $user->getPlaceOfIssue());
            $sheet->setCellValue("J{$row}", $user->getHometownAddress());
            $sheet->setCellValue("K{$row}", $user->getPermanentAddress());
            $sheet->setCellValue("L{$row}", $user->getRegisNumber());
            $sheet->setCellValue("M{$row}", $user->getJoinPlace());
            $sheet->setCellValue("N{$row}", $user->getCardPlace());
            $sheet->setCellValue("O{$row}", $user->getJoinDate() ? $user->getJoinDate()->format('d/m/Y') : '');
            $sheet->setCellValue("P{$row}", $user->getUnionRole());
            $sheet->setCellValue("Q{$row}", $user->getAssociation());
            $sheet->setCellValue("R{$row}", $user->getHonorMember());
            $sheet->setCellValue("S{$row}", $user->getJoinPartyDate() ? $user->getJoinPartyDate()->format('d/m/Y') : '');
            $sheet->setCellValue("T{$row}", $user->getPartyPosition());
            $sheet->setCellValue("U{$row}", $user->getSalaryStatus() ? 'Có' : 'Không');
            $sheet->setCellValue("V{$row}", $user->getUnionBookNumber() ? 'Có' : 'Không');
            $sheet->setCellValue("W{$row}", $user->getEduLevel());
            $sheet->setCellValue("X{$row}", $user->getProLevel());
            $sheet->setCellValue("Y{$row}", $user->getPolTheory());
            $sheet->setCellValue("Z{$row}", $user->getItLevel());
            $sheet->setCellValue("AA{$row}", $user->getLangLevel());
            $sheet->setCellValue("AB{$row}", $user->getJob());
            $sheet->setCellValue("AC{$row}", $user->getPhoneNumber());
            $sheet->setCellValue("AD{$row}", $user->getEmail());
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $filename = 'DANH_SACH_DOAN_VIEN.xlsx';
        $dispositionHeader = $response->headers->makeDisposition(
            'attachment',
            $filename
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
