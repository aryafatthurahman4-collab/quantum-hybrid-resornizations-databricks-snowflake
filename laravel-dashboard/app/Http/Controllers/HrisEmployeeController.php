<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HrisEmployeeController extends Controller
{
    /**
     * Get HRIS ITK employee list & department metrics.
     */
    public function employees(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'system' => 'HRIS ITK - Employee Management System',
            'employees' => [
                [
                    'id' => 1,
                    'name' => 'Arya Fatthurahman',
                    'email' => 'arya@hr.com',
                    'role' => 'AI Engineering Lead',
                    'department' => 'Quantum Artificial Intelligence',
                    'status' => 'Active',
                    'salary' => 'Rp 25.000.000'
                ],
                [
                    'id' => 2,
                    'name' => 'Budi Santoso',
                    'email' => 'budi@hr.com',
                    'role' => 'Senior Developer',
                    'department' => 'Software Engineering',
                    'status' => 'Active',
                    'salary' => 'Rp 18.000.000'
                ],
                [
                    'id' => 3,
                    'name' => 'Siti Rahma',
                    'email' => 'siti@hr.com',
                    'role' => 'HR Operations Specialist',
                    'department' => 'Human Capital',
                    'status' => 'Active',
                    'salary' => 'Rp 15.000.000'
                ]
            ],
            'summary' => [
                'total_karyawan' => 45,
                'satuan_kerja' => 6,
                'absensi_hari_ini' => '97.8%',
                'payroll_status' => 'Processed'
            ]
        ]);
    }

    /**
     * Get attendance recaps.
     */
    public function attendance(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'attendance_rate' => '97.8%',
            'on_time' => 42,
            'late' => 2,
            'permit_leave' => 1
        ]);
    }
}
