<?php

/**
 * Login
 */
class LoginController
{
  private $model;
  private $data;
  private $pdo;

  function __construct($model, $data, $pdo)
  {
    $this->model = $model;
    $this->data = $data;
    $this->pdo = $pdo;
  }

  function sign_in()
  {
    require_once("../slim-api/src/validation_functions.php");
    require_once('../slim-api/src/Models/Model.php');
    if (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] == true ) {
      return(false);
    }
    if (!isset($this->data['email']) || $this->data['email'] == '' || !isset($this->data['password']) || $this->data['password'] == '')
    {
      return(false);
    }
    $this->data['password'] = md5($this->data['password']);
    $mdl = new Model($this->model,$this->data,$this->pdo,'login_sign_in');
    $res_query = $mdl->openModel();
    if ($res_query){
      if (!isset($_SESSION['user_logged'])) {
        session_start();
      }
      $_SESSION['user_id'] = $res_query['id'];
      $_SESSION['user_logged'] = true;
      $_SESSION['user_name'] = $res_query['name'];
      $_SESSION['user_email'] = $res_query['email'];
    }
    return($res_query);
  }

  function logout()
  {
    require_once("../slim-api/src/validation_functions.php");
    require_once('../slim-api/src/Models/Model.php');
    if ((isset($_SESSION['user_logged']) && $_SESSION['user_logged'] == false) || !isset($_SESSION['user_logged'])) {
      return(false);
    }
    if ($this->data['logout'] != true) {
      return(false);
    }else{
      $_SESSION['user_logged'] = false;
      $_SESSION['user_name'] = null;
      $_SESSION['user_email'] = null;
      return(true);
    }
  }

  function set_token(){
    require_once("../slim-api/src/validation_functions.php");
    require_once('../slim-api/src/Models/Model.php');
    if ($this->data['user_id'] == null || !isset($this->data['user_id'])) {
      return(false);
    }
    $mdl = new Model($this->model,$this->data,$this->pdo,'login_set_token');
    $res_query = $mdl->openModel();
    if ($res_query) {
      $_SESSION['user_token'] = $this->data['token'];
    }
    return($res_query);
  }

  function get_token(){
    require_once("../slim-api/src/validation_functions.php");
    require_once('../slim-api/src/Models/Model.php');

    if (!isset($this->data['user_id']) || $this->data['user_id'] == null) {
      return(false);
    }
    $mdl = new Model($this->model,$this->data,$this->pdo,'login_get_token');
    $res_query = $mdl->openModel();
    return($res_query);
  }
}


?>
