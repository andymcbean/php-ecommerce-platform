<?php

function retailer_state()
    {
        global $db;

        $sql = "SELECT 
                    *
                FROM 
                    retailers
                ";
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        foreach ($result as $row)
            {
                $address = $row['address'];
                if($address != "")
                    {
                        $sql = "SELECT DISTINCT
                                    (state)
                                FROM 
                                    retailers
                                WHERE address != ''
                                ORDER BY 
                                    state ASC
                                ";
                        $statement = $db->prepare($sql);
                        $statement ->execute();
                        $result = $statement ->fetchAll();
                        $output = "";
                        foreach ($result as $row)
                            {
                                if($row['state'] == "")
                                    {
                                        $output .= "<div class='list-group-item checkbox'>
                                                    <label style='font-size: 12px;'><input onclick='changeMap(this.value)' type='checkbox' class='common_selector state' value='".$row['state']."'> Non US / Online only</label>
                                                    </div>";
                                    }
                                else
                                    {
                                        $output .= "<div class='list-group-item checkbox'>
                                                    <label style='font-size: 12px;'><input onclick='changeMap(this.value)' type='checkbox' class='common_selector state' value='".$row['state']."'> ".$row['state']."</label>
                                                    </div>";
                                    }                               
                            }  
                    }
            }
        return $output;
    }
  
function retailer_country()
    {
        global $db;

        $sql = "SELECT 
                    *
                FROM 
                    retailers
                ";
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        foreach ($result as $row)
            {
                $address = $row['address'];
                if($address != "")
                    {
                        $sql = "SELECT DISTINCT
                                    (country)
                                FROM 
                                    retailers
                                ORDER BY 
                                    country ASC
                                ";
                        $statement = $db->prepare($sql);
                        $statement ->execute();
                        $result = $statement ->fetchAll();
                        $output = "";
                        foreach ($result as $row)
                            {
                                $output .= "<div class='list-group-item checkbox'>
                                            <label style='font-size: 12px;'><input type='checkbox' class='common_selector country' value='".$row['country']."'> ".$row['country']."</label>
                                            </div>";           
                            } 
                    }
            }
        return $output;
    }
