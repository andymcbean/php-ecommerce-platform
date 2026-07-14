<?php
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);

function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = preg_replace("/[^a-zA-Z]/", "", $data);
    return $data;
  }

function test_input_pw($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

function trim_array(&$data) 
  { 
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
  }

function is_valid_filetype($ext) 
  {
      $ext = strtolower($ext);
      return in_array($ext, array('JPG', 'PNG', 'JPEG', 'jpg', 'jpeg', 'png', 'gif', 'mp4'));
  }

function logged_in() 
  {
  
  if((isset($_SESSION['email']) && $_SESSION['email'] != ''))
      {
        return true;
      } 
    else 
      {
        return false;
      }
  }

function logged_in_fb() 
  {
  
  if((isset($_SESSION['user_email_address']) && $_SESSION['user_email_address'] != ''))
      {
        return true;
      } 
    else 
      {
        return false;
      }
  }

function logged_in_google() 
  {
  
  if((isset($_SESSION['g_user_email_address']) && $_SESSION['g_user_email_address'] != ''))
      {
        return true;
      } 
    else 
      {
        return false;
      }
  }

function global_logged_in($email)
  {
    if (isset($_SESSION['g_user_email_address']))
        {
            $email .= $_SESSION['g_user_email_address'];
        }
    elseif (isset($_SESSION['user_email_address']))
        {
            $email .= $_SESSION['user_email_address'];
        }
    elseif (isset($_SESSION['email']))
        {
            $email .= $_SESSION['email'];
        }
    return $email;
  }

function wishlist($sku)
  {
    global $db;
    $sku = $_GET['sku'];

    if(isset($_SESSION['g_user_email_address']))
        {
            $email = $_SESSION['g_user_email_address'];
        }
    elseif(isset($_SESSION['user_email_address']))
        {
            $email = $_SESSION['user_email_address'];
        }
    elseif(isset($_SESSION['email']))
        {
            $email = $_SESSION['email'];
        }

    $sql = "SELECT 
                sku,
                email
            FROM 
                wishlist
            WHERE 
                email = '$email'
            AND 
                sku = '$sku'
            ";      
    $statement = $db ->prepare($sql);
    $statement ->execute();
    $result = $statement ->fetchAll();
    $total_row = $statement ->rowCount();

      if($total_row == 1)
        {
          return true;
        }
      else  
        {
          return false;
        }
  }
function duplicates($size, $description, $order_no)
  {
    global $db;
    $sql = "SELECT 
                id,
                size,
                description,
                order_no
            FROM 
                orders
            WHERE 
                size = '$size'
            AND 
                description = '$description'
            AND order_no = '$order_no'
            ";      
    $statement = $db ->prepare($sql);
    $statement ->execute();
    $result = $statement ->fetchAll();
    $total_row = $statement ->rowCount();

      if($total_row > 1)
        {
          return true;
        }
      else  
        {
          return false;
        }
  }
  
function get_failed_logins()
  {
    global $db;

    $ip = $_SERVER['REMOTE_ADDR'];
    $sql = "SELECT
                email,
                ip_addr,
                date_attempted
            FROM
                failed_logins
            WHERE
                ip_addr = '$ip'
            ";
    //echo $sql;
    $statement = $db->prepare($sql);
    $statement->execute();
    $statement->fetchAll();
    $total_row = $statement->rowCount();

      if ($total_row > 2)
          {
              return true;
          }
      else
          {
              return false;
          }
  }

function homepage_items()
	{
		global $db;
		
		$sql = "SELECT
                id,
                description,
                img,
                status,
                sku,
                active,
                retail_only
            FROM
                items
            WHERE 
                active = 'yes'
            AND retail_only = 0
            LIMIT 0, 16
            ";
		$statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $items = '';
		foreach($result as $row)
			{
        $items .= "<div class='overlay-slide'>
                      <a href='../decoupage/details?sku=".$row['sku']."'><img src='https://kingdomdesign.s3.eu-west-1.amazonaws.com/".$row['img']."'></a>
                      <div id='overlay-info'>
                        <h2>".$row['description']."</h2>
                        
                      </div>
                    </div>";
			}
	
		return $items;
    }

function log_failed_logins()
  {
    global $db;

    $email = $_POST['email'];
    $date_attempted = date('Y-m-d H-i-s');
    $ip  = $_SERVER['REMOTE_ADDR'];
    $sql = "INSERT INTO failed_logins (email, ip_addr, date_attempted) VALUES (:email, :ip_addr, :date_attempted)";

    $statement = $db->prepare($sql);
    $statement->bindParam(':email'         , $email,            PDO::PARAM_STR);
    $statement->bindParam(':ip_addr'          , $ip,                  PDO::PARAM_STR);
    $statement->bindParam(':date_attempted'   , $date_attempted,      PDO::PARAM_STR);
    try
      {
         $statement->execute();
      }
    catch(PDOException $e)
      {
          echo $e;
      }
   
  }

function user_logged_in()
  {
    global $db;

    $sql = "UPDATE
                users
            SET 
                logged_in = 1
            WHERE
                email  = '".$_SESSION['email']."'
            ";
            //echo $sql;
            $statement = $db->prepare($sql);
            $statement->execute();
  }

function user_logged_out()
  {
    global $db;

    $sql = "UPDATE
                users
            SET 
                logged_in = 0
            WHERE
                email  = '".$_SESSION['email']."'
            ";
            //echo $sql;
            $statement = $db->prepare($sql);
            $statement->execute();
  }

function user_admin()
  {
    global $db;
    $email = "";
    if(isset($_SESSION['email']))
      {
        $email = $_SESSION['email'];
      }
    elseif(isset($_SESSION['user_email_address']))
      {
        $email = $_SESSION['user_email_address'];
      }
    elseif(isset($_SESSION['g_user_email_address']))
      {
        $email = $_SESSION['g_user_email_address'];
      }
        $sql = "SELECT
                      id,
                      email, 
                      user_level
                FROM
                      users
                WHERE
                      email = '$email' 
                AND 
                      user_level = 'admin'
                ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

          if ($result)
              {
                return true;
              }
          else
              {
                return false;
              }
  }
    
function user_retailer()
  {
    
    global $db;
    $email = "";
    if(isset($_SESSION['email']))
      {
        $email = $_SESSION['email'];
      }
    elseif(isset($_SESSION['user_email_address']))
      {
        $email = $_SESSION['user_email_address'];
      }
    elseif(isset($_SESSION['g_user_email_address']))
      {
        $email = $_SESSION['g_user_email_address'];
      }
    $sql = "SELECT
                  id,
                  email, 
                  user_level,
                  retailer
            FROM
                  users
            WHERE
                  email = '$email'
            AND 
                  retailer = 'Yes'
            ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();

      if ($result)
          {
            return true;
          }
      else
          {
            return false;
          }
        
  }

function user_subscriber()
  {
    
    global $db;
    if(isset($_SESSION['email']))
      {
        $email = $_SESSION['email'];
      }
    elseif(isset($_SESSION['user_email_address']))
      {
        $email = $_SESSION['user_email_address'];
      }
    elseif(isset($_SESSION['g_user_email_address']))
      {
        $email = $_SESSION['g_user_email_address'];
      }
    $sql = "SELECT
                  id,
                  email,
                  subscriber
            FROM
                  users
            WHERE
                  email = '$email'
            ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
      foreach($result as $row)
        {
          if($row['subscriber'] == 1)
            {
              return true;
            }
          else
            {
              return false;
            }
        }
  }

function non_user_subscriber($email)
  {
    global $db;
    $sql = "SELECT
                  id,
                  email,
                  subscriber
            FROM
                  subscribers
            WHERE
                  email = '$email'
            ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
      foreach($result as $row)
        {
          if($row['subscriber'] == 1)
            {
              return true;
            }
          else
            {
              return false;
            }
        }
  }

function admin_email()
  {
    global $db;

    $sql = "SELECT
                  id,
                  email, 
                  user_level
            FROM
                  users
            WHERE
                  user_level = 'admin'
            ";
    //echo $sql;
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();

    foreach($result as $row)
      {
        $admin = $row['email'];
      }
  }

function delivery($order_no)
    {
      global $db;
      $order_no = $_POST['order_no'];
      $sql = "SELECT *
              FROM
                  delivery
              WHERE
                  order_no = '$order_no'
              ";
      $statement = $db->prepare($sql);
      $statement ->execute();
      $result = $statement ->fetchAll();
      $total_row = $statement->rowCount();
      if($total_row == 1)
          {
              return true;
          }
      else
          {
              return false;
          }
    }
function country_list()
    {
      global $db;

      $sql = "SELECT
                  id,
                  country_name
              FROM
                  country_list
              ";
      $statement = $db->prepare($sql);
      $statement ->execute();
      $result = $statement ->fetchAll();
      foreach($result as $row)
        {
          echo "<option name='".$row['country_name']."' id='".$row['id']."'>".$row['country_name']."</option>";
        }
    }

function country_delivery()
    {
      global $db;

      $sql = "SELECT
                  id,
                  country_name,
                  region
              FROM
                  country_list
              ";
      $statement = $db->prepare($sql);
      $statement ->execute();
      $result = $statement ->fetchAll();
      foreach($result as $row)
        {
          if(user_retailer())
            {
              $price = '0.00';
            }
          else
            {
              if($row['country_name'] == 'United States')
                {
                  $price = '5.00';
                }
              
              elseif($row['country_name'] == 'Canada')
                {
                  $price = '12.00';
                }
              elseif($row['country_name'] == 'Australia')
                {
                  $price = '18.00';
                }
              else
                {
                  $price = '16.00';
                }
              if($row['country_name'] == 'United Kingdom' || $row['region'] == 'eu')
                {
                  $price = '14.00';
                }
            }
          
          echo "<option name='".$price."' id='".$row['id']."'>".$row['country_name']."</option>";
        }
    }

function retail_agreement()
  {
    global $db;

    if(isset($_SESSION['email']))
      {
        $email = $_SESSION['email'];
      }
    elseif(isset($_SESSION['user_email_address']))
      {
        $email = $_SESSION['user_email_address'];
      }
    elseif(isset($_SESSION['g_user_email_address']))
      {
        $email = $_SESSION['g_user_email_address'];
      }
    $sql = "SELECT
                  id,
                  email,
                  agreement,
                  store_name,
                  signed,
                  date,
                  name
            FROM
                  retail_agreements
            WHERE
                  email = '$email'
            ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
      {
        $email = $row['email'];
        $agreement = html_entity_decode($row['agreement']);
        $store_name = $row['store_name'];
        $signed = $row['signed'];
        $date = $row['date'];
        $name = $row['name'];
      }
      echo $agreement;
  }

function retail_agreement_id()
  {
    
    global $db;
    if(isset($_SESSION['email']))
      {
        $email = $_SESSION['email'];
      }
    elseif(isset($_SESSION['user_email_address']))
      {
        $email = $_SESSION['user_email_address'];
      }
    elseif(isset($_SESSION['g_user_email_address']))
      {
        $email = $_SESSION['g_user_email_address'];
      }
    $sql = "SELECT
                  id,
                  email,
                  agreement,
                  store_name,
                  signed,
                  date,
                  name
            FROM
                  retail_agreements
            WHERE
                  email = '$email'
            ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
    $total_row = $statement->rowCount();
    if($total_row == 1)
        {
            return true;
        }
    else
        {
            return false;
        }
  }

function customer_retailer($email, $name)
  {
    global $db;
    $sql = "SELECT
                  id,
                  name,
                  email, 
                  user_level,
                  retailer
            FROM
                  users
            WHERE
                  email = '$email'
            OR name = '$name'
            AND
                  retailer  = 'Yes'
            ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();

      if ($result)
          {
            return true;
          }
      else
          {
            return false;
          }
        
  }

function retailer_us()
  {
    global $db;
    $email = "";
    if(isset($_SESSION['email']))
      {
        $email = $_SESSION['email'];
      }
    elseif(isset($_SESSION['user_email_address']))
      {
        $email = $_SESSION['user_email_address'];
      }
    elseif(isset($_SESSION['g_user_email_address']))
      {
        $email = $_SESSION['g_user_email_address'];
      }
    $sql = "SELECT
                  id,
                  email, 
                  user_level,
                  retailer,
                  us_retailer
            FROM
                  users
            WHERE
                  email = '$email'
            AND 
                  retailer = 'Yes'
            AND 
                  us_retailer = 'yes'
            ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();

      if ($result)
          {
            return true;
          }
      else
          {
            return false;
          }
        
  }

function stock_adjusted($stock_adjusted, $order_no)
  {
    global $db;
    $sql = "UPDATE 
                orders
            SET
                stock_adjusted=:stock_adjusted
            WHERE
                order_no = '$order_no'
            ";
    $statement = $db->prepare($sql);
    $statement->bindParam(':stock_adjusted'	,   $stock_adjusted,   PDO::PARAM_INT);
    try
        {
            $statement->execute();
        }
    catch(PDOException $e)
        {   
            echo $e;
        }
  }

function new_stock_a4($new_stock_a4, $sku)
  {
    global $db;
    $sql = "UPDATE 
                items
            SET
                stock_a4=:new_stock_a4
            WHERE
                sku = '$sku'
            ";
    $statement = $db->prepare($sql);
    $statement->bindParam(':new_stock_a4'	,   $new_stock_a4,   PDO::PARAM_INT);
    try
        {
            $statement->execute();
        }
    catch(PDOException $e)
        {   
            echo $e;
        }
  }

function new_stock_a3($new_stock_a3, $sku)
  {
    global $db;
    $sql = "UPDATE 
                items
            SET
                stock_a3=:new_stock_a3
            WHERE
                sku = '$sku'
            ";
    $statement = $db->prepare($sql);
    $statement->bindParam(':new_stock_a3'	,   $new_stock_a3,   PDO::PARAM_INT);
    try
        {
            $statement->execute();
        }
    catch(PDOException $e)
        {   
            echo $e;
        }
  }

  function new_stock_xl($new_stock_xl, $sku)
    {
      global $db;
      $sql = "UPDATE 
                  items
              SET
                  stock_xl=:new_stock_xl
              WHERE
                  sku = '$sku'
            ";
      $statement = $db->prepare($sql);
      $statement->bindParam(':new_stock_xl'	,   $new_stock_xl,   PDO::PARAM_INT);
      try
          {
              $statement->execute();
          }
      catch(PDOException $e)
          {   
              echo $e;
          }
    }

function new_stock_sc($new_stock_sc, $sku)
    {
      global $db;
      $sql = "UPDATE 
                  items
              SET
                  stock_scrapbook=:new_stock_sc
              WHERE
                  sku = '$sku'
            ";
      $statement = $db->prepare($sql);
      $statement->bindParam(':new_stock_sc'	,   $new_stock_sc,   PDO::PARAM_INT);
      try
          {
              $statement->execute();
          }
      catch(PDOException $e)
          {   
              echo $e;
          }
    }

function new_stock_chip($new_stock_chip, $sku)
    {
      global $db;
      $sql = "UPDATE 
                  items
              SET
                  stock_chipboard=:new_stock_chip
              WHERE
                  sku = '$sku'
            ";
      $statement = $db->prepare($sql);
      $statement->bindParam(':new_stock_chip'	,   $new_stock_chip,   PDO::PARAM_INT);
      try
          {
              $statement->execute();
          }
      catch(PDOException $e)
          {   
              echo $e;
          }
    }

function count_stock($count_code, $count_size, $count_qty)
    {
      global $db;
      $skus = $count_code;
      $new_sku = substr($skus, 0, -2);
        
      $size = $count_size;

      $qtys = $count_qty;
      $qty = trim_array($qtys);

      $sql = "SELECT
                  sku,
                  stock_$size AS actual,
                  $size
              FROM
                  items
              WHERE
                  sku = '$new_sku'
              ";
              //echo $sql; die();
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row)
          {
            $actual = $row['actual'];
            if($actual < $qty)
              {
                return true;
              }
            else
              {
                return false;
              }
          }
        
    }

function product_qty($sku, $size)
  {
    global $db;
    $sku_code = substr($sku, 0, -2);

    $sql = "SELECT
                  sku,
                  stock_$size AS actual,
                  $size
              FROM
                  items
              WHERE
                  sku = '$sku_code'
              ";
              //echo $sql;
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row)
          {
            $actual = $row['actual'];
          }
          return $actual;
  }
?>