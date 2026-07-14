<?php
//include 'connect.php';  
function items_type()
    {
        global $db;

       $sql = "SELECT DISTINCT
                        (type)
                FROM 
                        items
                ORDER BY 
                        type ASC
        ";

        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                    </div>";
            } 

        return $output;
    }                      

function units_width()
    {
        global $db;   
        $sql = "SELECT DISTINCT
                        (width)
                FROM 
                        units
                ORDER BY 
                        width ASC
            ";

            $statement = $db->prepare($sql);
            $statement ->execute();
            $result = $statement ->fetchAll();
            $output = "";
            foreach ($result as $row)
                {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector width' value='".$row['width']."'> ".$row['width']."mm</label>
                    </div>";
                }
            return $output;
    }
function storage_type()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (type)
                FROM 
                        storage
                ORDER BY 
                        type ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                    </div>";
            }
        return $output;
    }

function storage_width()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (width)
                FROM 
                        storage
                ORDER BY 
                        width ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                if ($row['width'] < 1)
                    {
                        $output = "";
                    }
                else
                    {
                        $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector width' value='".$row['width']."'> ".$row['width']."mm</label>
                    </div>";
                    }
                
            }
        return $output;
    }

function storage_height()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (height)
                FROM 
                        storage
                ORDER BY 
                        height ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                if ($row['height'] < 1)
                    {
                        $output = "";
                    }
                else
                    {
                        $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector height' value='".$row['height']."'> ".$row['height']."mm</label>
                    </div>";
                    }
                
            }
        return $output;
    }

function handles_type()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (type)
                FROM 
                        handles
                ORDER BY 
                        type ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output.= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                    </div>";
            }
        return $output;
    }

function handles_finish()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (finish)
                FROM 
                        handles
                ORDER BY 
                        finish ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output.= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector finish' value='".$row['finish']."'> ".$row['finish']."</label>
                    </div>";
            }
        return $output;
    }

function worktop_type()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (type)
                FROM 
                        worktops
                ORDER BY 
                        type ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                    </div>";
            }
        return $output;
    }
    
function worktop_material()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (material)
                FROM 
                        worktops
                ORDER BY 
                        material ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector material' value='".$row['material']."'> ".$row['material']."</label>
                    </div>";
            }
        return $output;
    }

function flooring_type()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (type)
                FROM 
                        flooring
                ORDER BY 
                        type ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                    </div>";
            }
        return $output;
    }

function flooring_thickness()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (thickness)
                FROM 
                        flooring
                ORDER BY 
                        thickness ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                if ($row['thickness'] < 1)
                    {
                        $output = "";
                    }
                else
                    {
                        $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector thickness' value='".$row['thickness']."'> ".$row['thickness']."</label>
                    </div>";
                    }
                
            }
        return $output;
    }

function sink_tap_type()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (type)
                FROM 
                        sinks_taps
                ORDER BY 
                        type ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                                <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                            </div>";
            }
        return $output;
    }

function item_size_a3()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (a3)
                FROM 
                        items
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                                <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['a3']."'> ".$row['a3']."</label>
                            </div>";
            }
        return $output;
    }

function item_size_a4()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (a4)
                FROM 
                        items
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                                <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['a4']."'> ".$row['a4']."</label>
                            </div>";
            }
        return $output;
    }

function item_size_xl()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (xl)
                FROM 
                        items
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                                <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['xl']."'> ".$row['xl']."</label>
                            </div>";
            }
        return $output;
    }

function appliances_fuel()
    {
        global $db;
        $sql = "SELECT DISTINCT
                        (fuel)
                FROM 
                        appliances
                ORDER BY 
                        fuel ASC
            ";
    
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector fuel' value='".$row['fuel']."'> ".$row['fuel']."</label>
                    </div>";
            }
        return $output;
    }

function range_type($range)
    {
        global $db;
        $range = $_GET['name'];
        $sql = "SELECT DISTINCT
                        (type)
                FROM 
                        range_accessories
                WHERE
                        range_name = '$range'
                ORDER BY 
                        type ASC
            ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $total_row = $statement->rowCount();
        $output = "";
        if($total_row > 0)
            {
                foreach ($result as $row)
                    {
                        $output .= "<div class='list-group-item checkbox'>
                                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                                    </div>";
                    }
            }
        else
            {
                $output = "";
            }
        
        return $output;
    }

function range_name($range)
    {
        global $db;
        $range = $_GET['name'];
        $sql = "SELECT DISTINCT
                        (range_name)
                FROM 
                        range_accessories
                WHERE
                        range_name = '$range'
                ORDER BY 
                        range_name ASC
            ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $total_row = $statement->rowCount();
        $output = "";
        if($total_row > 0)
            {
                foreach ($result as $row)
                    {
                        $output .= "<div class='list-group-item checkbox'>
                                        <label style='font-size: 12px;'><input type='checkbox' checked class='common_selector range_name' value='".$row['range_name']."'> ".$row['range_name']."</label>
                                    </div>";
                    }
            }
        else
            {
                $output = "";
            }
        
        return $output;
    }

function unit_range_price($range)
    {
        global $db;
        $range = $_GET['name'];
        if($range == 'Milbourne Porcelain')
            {
                $range = 'milbourne_porcelain';
            }
        if($range == 'Milbourne')
            {
                $range = 'milbourne';
            }
        if($range == 'Belsay')
            {
                $range = 'belsay';
            }
        if($range == 'Fitzroy')
            {
                $range = 'fitzroy';
            }
        if($range == 'Broadoak')
            {
                $range = 'broadoak';
            }
        if($range == 'Broadoak Natural')
            {
                $range = 'broadoak_natural';
            }
        if($range == 'Porter Gloss')
            {
                $range = 'porter_gloss';
            }
        if($range == 'Porter Matt')
            {
                $range = 'porter_matt';
            }
        if($range == 'Mornington Beaded')
            {
                $range = 'mornington_beaded';
            }
        if($range == 'Mornington Shaker')
            {
                $range = 'mornington_shaker';
            }
        if($range == 'Remo')
            {
                $range = 'remo';
            }
        if($range == 'Lichfield')
            {
                $range = 'lichfield';
            }
        if($range == 'Hunton')
            {
                $range = 'hunton';
            }
        if($range == 'Clarendon')
            {
                $range = 'clarendon';
            }

        $sql = "SELECT 
                    '$range'
                FROM 
                    units
                
                
            ";
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        //echo $sql;
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox hide'>
                                <label style='font-size: 12px;'><input type='checkbox' checked disabled class='common_selector ".$range."' value='".$range."'> ".$range."</label>
                            </div>";
            }
        return $output;
    }

function units_type_range($range)
    {
        global $db;
        $range = $_GET['name'];
        if($range == 'Milbourne Porcelain')
            {
                $range = 'milbourne_porcelain';
            }
        if($range == 'Milbourne')
            {
                $range = 'milbourne';
            }
        if($range == 'Belsay')
            {
                $range = 'belsay';
            }
        if($range == 'Fitzroy')
            {
                $range = 'fitzroy';
            }
        if($range == 'Broadoak')
            {
                $range = 'broadoak';
            }
        if($range == 'Broadoak Natural')
            {
                $range = 'broadoak_natural';
            }
        if($range == 'Porter Gloss')
            {
                $range = 'porter_gloss';
            }
        if($range == 'Porter Matt')
            {
                $range = 'porter_matt';
            }
        if($range == 'Mornington Beaded')
            {
                $range = 'mornington_beaded';
            }
        if($range == 'Mornington Shaker')
            {
                $range = 'mornington_shaker';
            }
        if($range == 'Remo')
            {
                $range = 'remo';
            }
        if($range == 'Lichfield')
            {
                $range = 'lichfield';
            }
        if($range == 'Hunton')
            {
                $range = 'hunton';
            }
        if($range == 'Clarendon')
            {
                $range = 'clarendon';
            }

       $sql = "SELECT DISTINCT
                        (type)
               FROM 
                        units
               WHERE ".$range." > '0'
                
        ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                    </div>";
            } 

        return $output;
    }

function units_width_range($range)
    {
        global $db;
        $range = $_GET['name'];
        if($range == 'Milbourne Porcelain')
            {
                $range = 'milbourne_porcelain';
            }
        if($range == 'Milbourne')
            {
                $range = 'milbourne';
            }
        if($range == 'Belsay')
            {
                $range = 'belsay';
            }
        if($range == 'Fitzroy')
            {
                $range = 'fitzroy';
            }
        if($range == 'Broadoak')
            {
                $range = 'broadoak';
            }
        if($range == 'Broadoak Natural')
            {
                $range = 'broadoak_natural';
            }
        if($range == 'Porter Gloss')
            {
                $range = 'porter_gloss';
            }
        if($range == 'Porter Matt')
            {
                $range = 'porter_matt';
            }
        if($range == 'Mornington Beaded')
            {
                $range = 'mornington_beaded';
            }
        if($range == 'Mornington Shaker')
            {
                $range = 'mornington_shaker';
            }
        if($range == 'Remo')
            {
                $range = 'remo';
            }
        if($range == 'Lichfield')
            {
                $range = 'lichfield';
            }
        if($range == 'Hunton')
            {
                $range = 'hunton';
            }
        if($range == 'Clarendon')
            {
                $range = 'clarendon';
            }

       $sql = "SELECT DISTINCT
                        (width)
               FROM 
                        units
               WHERE ".$range." > '0'
                
        ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector width' value='".$row['width']."'> ".$row['width']."</label>
                    </div>";
            } 

        return $output;
    }
    
function storage_width_range($range)
    {
        global $db;
        $range = $_GET['name'];
        if($range == 'Milbourne Porcelain')
            {
                $range = 'milbourne_porcelain';
            }
        if($range == 'Milbourne')
            {
                $range = 'milbourne';
            }
        if($range == 'Belsay')
            {
                $range = 'belsay';
            }
        if($range == 'Fitzroy')
            {
                $range = 'fitzroy';
            }
        if($range == 'Broadoak')
            {
                $range = 'broadoak';
            }
        if($range == 'Broadoak Natural')
            {
                $range = 'broadoak_natural';
            }
        if($range == 'Porter Gloss')
            {
                $range = 'porter_gloss';
            }
        if($range == 'Porter Matt')
            {
                $range = 'porter_matt';
            }
        if($range == 'Mornington Beaded')
            {
                $range = 'mornington_beaded';
            }
        if($range == 'Mornington Shaker')
            {
                $range = 'mornington_shaker';
            }
        if($range == 'Remo')
            {
                $range = 'remo';
            }
        if($range == 'Lichfield')
            {
                $range = 'lichfield';
            }
        if($range == 'Hunton')
            {
                $range = 'hunton';
            }
        if($range == 'Clarendon')
            {
                $range = 'clarendon';
            }

       $sql = "SELECT DISTINCT
                        (width)
               FROM 
                        storage
               WHERE ".$range." > '0'
                
        ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector width' value='".$row['width']."'> ".$row['width']."</label>
                    </div>";
            } 

        return $output;
    } 

function storage_type_range($range)
    {
        global $db;
        $range = $_GET['name'];
        if($range == 'Milbourne Porcelain')
            {
                $range = 'milbourne_porcelain';
            }
            if($range == 'Milbourne')
            {
                $range = 'milbourne';
            }
        if($range == 'Belsay')
            {
                $range = 'belsay';
            }
        if($range == 'Fitzroy')
            {
                $range = 'fitzroy';
            }
        if($range == 'Broadoak')
            {
                $range = 'broadoak';
            }
        if($range == 'Broadoak Natural')
            {
                $range = 'broadoak_natural';
            }
        if($range == 'Porter Gloss')
            {
                $range = 'porter_gloss';
            }
        if($range == 'Porter Matt')
            {
                $range = 'porter_matt';
            }
        if($range == 'Mornington Beaded')
            {
                $range = 'mornington_beaded';
            }
        if($range == 'Mornington Shaker')
            {
                $range = 'mornington_shaker';
            }
        if($range == 'Remo')
            {
                $range = 'remo';
            }
        if($range == 'Lichfield')
            {
                $range = 'lichfield';
            }
        if($range == 'Hunton')
            {
                $range = 'hunton';
            }
        if($range == 'Clarendon')
            {
                $range = 'clarendon';
            }

       $sql = "SELECT DISTINCT
                        (type)
               FROM 
                        storage
               WHERE ".$range." > '0'
                
        ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector type' value='".$row['type']."'> ".$row['type']."</label>
                    </div>";
            } 

        return $output;
    }

function storage_height_range($range)
    {
        global $db;
        $range = $_GET['name'];
        if($range == 'Milbourne Porcelain')
            {
                $range = 'milbourne_porcelain';
            }
            if($range == 'Milbourne')
            {
                $range = 'milbourne';
            }
        if($range == 'Belsay')
            {
                $range = 'belsay';
            }
        if($range == 'Fitzroy')
            {
                $range = 'fitzroy';
            }
        if($range == 'Broadoak')
            {
                $range = 'broadoak';
            }
        if($range == 'Broadoak Natural')
            {
                $range = 'broadoak_natural';
            }
        if($range == 'Porter Gloss')
            {
                $range = 'porter_gloss';
            }
        if($range == 'Porter Matt')
            {
                $range = 'porter_matt';
            }
        if($range == 'Mornington Beaded')
            {
                $range = 'mornington_beaded';
            }
        if($range == 'Mornington Shaker')
            {
                $range = 'mornington_shaker';
            }
        if($range == 'Remo')
            {
                $range = 'remo';
            }
        if($range == 'Lichfield')
            {
                $range = 'lichfield';
            }
        if($range == 'Hunton')
            {
                $range = 'hunton';
            }
        if($range == 'Clarendon')
            {
                $range = 'clarendon';
            }

       $sql = "SELECT DISTINCT
                        (height)
               FROM 
                        storage
               WHERE ".$range." > '0'
                
        ";
        //echo $sql;
        $statement = $db->prepare($sql);
        $statement ->execute();
        $result = $statement ->fetchAll();
        $output = "";
        foreach ($result as $row)
            {
                $output .= "<div class='list-group-item checkbox'>
                        <label style='font-size: 12px;'><input type='checkbox' class='common_selector height' value='".$row['height']."'> ".$row['height']."</label>
                    </div>";
            } 

        return $output;
    }
?>