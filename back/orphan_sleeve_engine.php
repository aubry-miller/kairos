<?php
function orphan_sleeve($temp_id){
    // Recover the informations of the reference command
    $technical_informations=select_temp_orders_by_id($temp_id);

    // We get the ids corresponding to the labels to be able to compare
    $product_type_id=get_product_type_id_by_label($technical_informations['temp_product_type']);
    $rubber_id=get_rubber_id_by_label($technical_informations['temp_rubber']);
    $notch_id=get_notch_id_by_label($technical_informations['temp_notch']);
    $fiber_id=get_fiber_id_by_label($technical_informations['temp_fiber']);



    // Change any ',' to '.' to compare
    $technical_informations['temp_sleeve_length']=str_replace(",", ".", $technical_informations['temp_sleeve_length']);
    $technical_informations['temp_table_length']=str_replace(",", ".", $technical_informations['temp_table_length']);
    $technical_informations['temp_sleeve_offset']=str_replace(",", ".", $technical_informations['temp_sleeve_offset']);
    $technical_informations['temp_mandrel_diameter']=str_replace(",", ".", $technical_informations['temp_mandrel_diameter']);
    $technical_informations['temp_developement']=str_replace(",", ".", $technical_informations['temp_developement']);
    $technical_informations['temp_fiber_thickness']=str_replace(",", ".", $technical_informations['temp_fiber_thickness']);
    $technical_informations['temp_cutback_diameter']=str_replace(",", ".", $technical_informations['temp_cutback_diameter']);

    // We make a first selection of the orphaned sleeves that could correspond (without worrying about the stage of manufacture at which they were when production was stopped)
    $orphans=select_orphelan_by_specifications($technical_informations['temp_mandrel_diameter'], $technical_informations['temp_mandrel_form'], $rubber_id, $fiber_id, $technical_informations['temp_fiber_thickness'], $technical_informations['temp_developement'], $technical_informations['temp_sleeve_length']);

    $n=0;
    // If the rectification step is in finished or In progress status it is necessary to check the table, the cutbacks, the notches and the chips
    foreach($orphans as $orphan){
        
        $grinding=select_planning_task_by_piece_id_and_step_id($orphan['pc_id'],'4');
        
        /*
        orphan status :
            • 1 => directly usable
            • 2 => rectification started but not finished, check where the production stopped physically
            • 3 => the next task is not finished
            • 4 => it is necessary to rework the piece to adapt it
        */

        if(isset($grinding[0]['pt_status']) && ($grinding[0]['pt_status'] =='Finished' || $grinding[0]['pt_status'] =='In progress')){
            
            
            // The rectification step has been started, so we have to compare the above mentioned elements
            if($technical_informations['temp_table_length'] == $orphan['pc_table_length'] && $technical_informations['temp_sleeve_offset'] == $orphan['pc_sleeve_offset'] && $technical_informations['temp_cutback'] == $orphan['pc_cutback'] && $technical_informations['temp_cutback_diameter'] == $orphan['pc_cutback_diameter'] && $technical_informations['temp_notch'] == $orphan['pc_notch_id'] && $technical_informations['temp_notch_position'] == $orphan['pc_notch_position'] && $technical_informations['temp_chip'] == $orphan['pc_chip']){
                
                // The orphan fits perfectly but you have to check if the manufacturing is finished or not
                if($grinding[0]['pt_status'] =='Finished'){
                    // The orphan can be directly used for the command
                    // We add it to the object we will return
                    $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                    $orphan_return[$n]['step']= '4';
                    $orphan_return[$n]['status']= '1';
                    $n++;

                } else {

                    // The orphan can be matched, see the finishing steps to be completed
                    // We add it to the object we will return
                    $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                    $orphan_return[$n]['step']= '4';
                    $orphan_return[$n]['status']= '2';
                    $n++;
                }
            } else{
                // It is necessary to rework the piece to adapt it
                // We add it to the object we will return
                $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                $orphan_return[$n]['step']= '4';
                $orphan_return[$n]['status']= '4';
                $n++;
            }

        } else {
            // The correction is not started, we can use the orphan without problem
            // We are looking for the stage at which the production has stopped
            $plans=select_planning_task_by_piece_id($orphan['pc_id']);
            foreach($plans as $plan){
                if($plan['pt_status'] == "Finished"){
                    // We add it to the object we will return
                    $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                    $orphan_return[$n]['step']= $plan['pt_step_id'];
                    $orphan_return[$n]['status']= '3 '.$plan['pt_step_id'];
                } else{
                    // When the task is not Finished, we stop the loop because the manufacturing was stopped there
                    break;
                }
            }
        
            $n++;
        }
    }
    $orphan_return['number']=$n;

    return $orphan_return;
}