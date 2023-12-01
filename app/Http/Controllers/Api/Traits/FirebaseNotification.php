<?php

namespace App\Http\Controllers\Api\Traits;

use App\Models\Notification;
use App\Models\PhoneToken;
use App\Models\User;

trait FirebaseNotification{

    //firebase server key
    private string $serverKey = 'AAAAmQB5gtM:APA91bHcgetfarwsYS2rCkzZ-5ZVZgEvBbT4TsdwkkuuAxluFgwePgyhhPQrYPO9SsMRdMuzEbrLchZuzK44RakbBSwgZbBe0ghlBAxC5Z_TTtlqGmLY-_yud2KbMUk9hrcEDrvq4qfC';


    public function sendFirebaseNotification($data,$season_id = null,$student_id = null,$group_ids = [],$statusStoreNotification = false){

        $url = 'https://fcm.googleapis.com/fcm/send';


        if($student_id != null){

        ############# في حاله لو الارسال مرسل لطالب معين #################
        $userIds = User::query()
            ->where('id','=',$student_id)
            ->pluck('id')
            ->toArray();

        }elseif ($season_id != null && $group_ids !=  null){

       ################## في حاله لو الارسال مرسل لطلبه محددين من قبل المدرس ############
        $userIds = User::query()
            ->whereIn('id',$group_ids)
            ->pluck('id')
            ->toArray();

        }else{
          
            ########### في حاله لو الارسال مرسل لجميع طلبه هذا الصف الدراسي #################
            $userIds = User::query()
                ->where('season_id','=',$season_id)
                ->pluck('id')
                ->toArray();

        }

        $tokens = PhoneToken::query()
            ->whereIn('user_id',$userIds)
            ->pluck('token')
            ->toArray();

      if(!$statusStoreNotification){
          Notification::create([
              'title' => $data['title'],
              'body' => $data['body'],
              'user_id' => $student_id,
          ]);
      }

        $fields = array(
            'registration_ids' => $tokens,
            'data' => $data,
            "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],

            ]
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }




    ############################# ارسال اشعار للطلبه عند اضافه فيديو شرح او فيديو اساسيات او فيديو مراجعه ################
    public function sendFirebaseNotificationWhenAddedVideo($data,$season_id,$video_type,$video_id){

        $url = 'https://fcm.googleapis.com/fcm/send';

         ######### في حاله لو الارسال مرسل لجميع طلبه هذا الصف الدراسي #######
        $userIds = User::query()
            ->where('season_id','=',$season_id)
            ->pluck('id')
            ->toArray();

        $tokens = PhoneToken::query()
            ->whereIn('user_id',$userIds)
            ->pluck('token')
            ->toArray();

        Notification::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'notification_type' => "video",
            'user_type' => "all_students",
            'video_type' => $video_type,
            'video_id' =>  $video_id,
            'season_id' =>  $season_id,
        ]);


        $fields = array(
            'registration_ids' => $tokens,
            'data' => $data,
            "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],

            ]
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }


    ############################# ارسال اشعار للطلبه عند اضافه امتحان فصول او امتحان دروس او واجب او امتحان شامل او امتحان لايف او امتحان ورقي ################

    public function sendFirebaseNotificationWhenAddedExam($data,$season_id,$exam_type,$exam_id){

        $url = 'https://fcm.googleapis.com/fcm/send';

        ######### في حاله لو الارسال مرسل لجميع طلبه هذا الصف الدراسي #######
        $userIds = User::query()
            ->where('season_id','=',$season_id)
            ->pluck('id')
            ->toArray();

        $tokens = PhoneToken::query()
            ->whereIn('user_id',$userIds)
            ->pluck('token')
            ->toArray();

        Notification::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'notification_type' => "exam",
            'user_type' => "all_students",
            'exam_type' => $exam_type,
            'exam_id' =>  $exam_id,
            'season_id' => $season_id,
        ]);


        $fields = array(
            'registration_ids' => $tokens,
            'data' => $data,
            "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],

            ]
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

}
