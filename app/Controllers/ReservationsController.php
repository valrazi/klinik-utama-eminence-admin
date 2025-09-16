<?php

namespace App\Controllers;

use App\Models\DoctorScheduleModel;
use App\Models\ReservationModel;

class ReservationsController extends BaseController
{
    public function index()
    {
        $reservationModel = new ReservationModel();
        $reservations = $reservationModel->allWithDetail();

        $data = [
            'title' => 'List Reservasi',
            'reservations' => $reservations
        ];

        return view('backoffice/list-reservation', $data);
    }

    public function show($id)
    {
        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->oneWithDetail($id);

        if (!$reservation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Reservasi dengan ID $id tidak ditemukan");
        }

        $data = [
            'title' => 'Detail Reservasi',
            'reservation' => $reservation
        ];

        return view('backoffice/reservations/show', $data);
    }

    public function reschedule($id)
    {
        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->oneWithDetail($id);

        if (!$reservation) {
            return redirect()->to(base_url('reservations'))
                ->with('error', 'Reservasi tidak ditemukan');
        }

        return view('backoffice/reschedule-reservation', [
            'title' => 'Reschedule Reservation',
            'reservation' => $reservation
        ]);
    }

    public function getSchedules($id)
    {
        $reservationModel = new ReservationModel();
        $scheduleModel    = new DoctorScheduleModel();

        $reservation = $reservationModel->find($id);
        if (!$reservation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Reservation not found'
            ]);
        }

        $date = $this->request->getPost('date');
        $formattedDate = date('Y-m-d', strtotime($date));

        // Doctor service case
        if ($reservation['service'] === 'injury') {
            $schedules = $scheduleModel
                ->select('start_time, end_time')
                ->where('doctor_id', $reservation['staff_id'])
                ->where('is_available', 1)
                ->where('schedule_date', $formattedDate)
                ->orderBy('schedule_date DESC, start_time ASC')
                ->findAll();

            return $this->response->setJSON(['success' => true, 'schedules' => $schedules]);
        }

        // Therapist service case
        $staffReservations = $reservationModel
            ->select('start_time, end_time')
            ->where('staff_id', $reservation['staff_id'])
            ->where('schedule_date', $formattedDate)
            ->orderBy('start_time', 'ASC')
            ->findAll();

        if (count($staffReservations) >= 3) {
            return $this->response->setJSON(['success' => true, 'schedules' => []]);
        }

        $arrSchedule = [
            '06:00:00-07:00:00',
            '07:00:00-08:00:00',
            '08:00:00-09:00:00',
            '09:00:00-10:00:00',
            '10:00:00-11:00:00',
            '11:00:00-12:00:00',
            '12:00:00-13:00:00',
            '13:00:00-14:00:00',
            '14:00:00-15:00:00',
            '15:00:00-16:00:00',
            '16:00:00-17:00:00',
            '17:00:00-18:00:00',
            '18:00:00-19:00:00',
            '19:00:00-20:00:00',
        ];

        foreach ($staffReservations as $sr) {
            $time = "{$sr['start_time']}-{$sr['end_time']}";
            $key = array_search($time, $arrSchedule);
            if ($key !== false) {
                unset($arrSchedule[$key]);
            }
        }

        $arrScheduleMapped = array_map(function ($s) {
            [$start, $end] = explode('-', $s);
            return ['start_time' => $start, 'end_time' => $end];
        }, $arrSchedule);

        return $this->response->setJSON(['success' => true, 'schedules' => array_values($arrScheduleMapped)]);
    }

    public function updateReschedule($id)
    {
        $reservationModel = new ReservationModel();
        $doctorScheduleModel = new DoctorScheduleModel();
        $db = \Config\Database::connect();

        $reservation = $reservationModel->find($id);

        if (!$reservation) {
            return redirect()->to(base_url('backoffice/reservations'))
                ->with('error', 'Reservasi tidak ditemukan');
        }

        $date = $this->request->getPost('date');
        $time = $this->request->getPost('time'); // format: start-end
        [$start, $end] = explode('-', $time);

        // check if new schedule already taken
        $exists = $reservationModel
            ->where('staff_id', $reservation['staff_id'])
            ->where('schedule_date', $date)
            ->where('start_time', $start)
            ->where('end_time', $end)
            ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Jadwal sudah terpakai!');
        }

        // check doctor schedule availability
        $doctorSchedule = $doctorScheduleModel
            ->where([
                'schedule_date' => $date,
                'start_time'    => $start,
                'end_time'      => $end,
            ])
            ->first();

        if (!$doctorSchedule || !$doctorSchedule['is_available']) {
            return redirect()->back()->with('error', 'Jadwal dokter tidak tersedia!');
        }

        // Begin transaction
        $db->transStart();

        // create new reservation with reschedule_of reference
        $reservationModel->insert([
            'patient_id'     => $reservation['patient_id'],
            'staff_id'       => $reservation['staff_id'],
            'service'        => $reservation['service'],
            'schedule_date'  => $date,
            'start_time'     => $start,
            'end_time'       => $end,
            'status'         => 'booked',
            'reschedule_of'  => $id
        ]);

        // mark old reservation as rescheduled
        $reservationModel->update($id, ['status' => 'rescheduled']);

        // make new schedule unavailable
        $doctorScheduleModel->update($doctorSchedule['id'], ['is_available' => false]);

        // make old schedule available again
        $doctorScheduleOld = $doctorScheduleModel
            ->where([
                'schedule_date' => $reservation['schedule_date'],
                'start_time'    => $reservation['start_time'],
                'end_time'      => $reservation['end_time'],
            ])
            ->first();

        if ($doctorScheduleOld) {
            $doctorScheduleModel->update($doctorScheduleOld['id'], ['is_available' => true]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Reschedule gagal, coba lagi.');
        }

        return redirect()->to(base_url("backoffice/reservations"))
            ->with('success', 'Reservasi berhasil direschedule');
    }
}
