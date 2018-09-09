<?php

namespace app\controllers;

use yii\rest\ActiveController;
use Yii;
use app\models\LoginForm;
use app\models\USERSSO;
use yii\filters\auth\HttpBasicAuth;
use yii\web\UnauthorizedHttpException;

class ApiController extends ActiveController
{
    public $modelClass = 'app\models\USERSSO';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }

    public function auth($username, $password)
    {
        $user = \app\models\USERSSO::findByUsername($username);
        if(!$username or !$password or !$user)
            //return false;
            //OR
            throw new UnauthorizedHttpException( "There is an error!" );
        if ($user->validatePassword($password) && $user == "cn") 
            return $user;
        else
            //return false;
            //OR
            throw new UnauthorizedHttpException( "Wrong username or password!" );
    }

    public function actionLogin(){
        $error = NULL;
        $response = array();
        if (Yii::$app->request->getRawBody()){
            $model = Yii::createObject(LoginForm::className());
            $data=json_decode(Yii::$app->request->getRawBody());
            // var_dump($data);exit();
            $model->username = $data->username;
            $model->password = $data->password;
            // var_dump($data->email);exit();
            if ($model->login()){
                $user = USERSSO::findOne(["USERNAME" => $model->username]);
                $response["user"]=[
                        "id" => $user->id,
                        "username" => $user->USERNAME,
                        "email" => $user->EMAIL,
                        "dtcrea" => $user->DTCREA,
                        "level_id" => $user->LEVEL_ID,
                        "nama" => $user->NAMAUSER,
                        "wil" => $user->WIL,
                        "nm_wil" => $user->NMWIL,
                        "lap" => $user->LAP,
                        "jabatan" => $user->JABATAN,
                        "id_peg" => $user->IDPEG,
                        "st_user" => $user->STUSER,             
                    ];
                    $response["error"] = FALSE;
            }else{
                if ($model->getErrors() != NULL){
                    foreach ($model->getErrors() as $errors){
                        $a = trim($errors[0], ".");
                        $error .= $a." ";
                    }
                }         
                $response["error"] = TRUE;
                $response["error_msg"] = $error;
            }
        }else{
            $response["error"] = TRUE;
            $response["user"]["message"] = "login failed";
        }
//        array_push($response["user"],$data);
        echo json_encode($response);
    }
}