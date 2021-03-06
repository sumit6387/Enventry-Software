<?php
namespace App\Functions;

use App\Models\OrderHistory;
use App\Models\User;
use Carbon\Carbon;
use Mail;
use PDF;

class AllFunction
{
    public function sendIdPassword($email, $password)
    {
        $user = User::where('email', $email)->get()->first();
        $to_name = 'User';
        $to_email = $email;
        $data = ['password' => $password, 'name' => $user->name, 'email' => $email];
        $status = Mail::send('emails.sendidpassword', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Registration Of Client');
            $message->from('smartenventry@gmail.com', 'Smart Enventry');
        });
        return true;
    }

    public function sendForgotPassword($email)
    {
        $user = User::where('email', $email)->get()->first();
        $to_name = $user->name;
        $to_email = $email;
        $em = md5($email);
        $data = ['name' => $user->name, 'email' => $em];
        $status = Mail::send('emails.forgotEmail', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Forgot Password');
            $message->from('smartenventry@gmail.com', 'Smart Enventry');
        });
        return true;
    }

    public function todayHistoryEmail($email)
    {
        $user = User::where('email', $email)->get()->first();
        $to_name = $user->name;
        $to_email = $email;
        $orderhistory = OrderHistory::where('client_id', $email)->whereDate('created_at', Carbon::today())->get();
        $data = ['name' => $user->name, 'data' => $orderhistory];
        $pdf = PDF::loadView('emails.historyOfDay', $data);
        $status = Mail::send('emails.historyOfDay', $data, function ($message) use ($to_name, $to_email, $pdf) {
            $message->to($to_email, $to_name)
                ->subject('Today History(' . date('d-m-y') . ")");
            $message->from('smartenventry@gmail.com', 'Online Web Care')
                ->attachData($pdf->output(), "todayHistory.pdf");
        });
        return true;
    }
}
