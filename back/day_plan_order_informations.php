<div class="offcanvas offcanvas-end" tabindex="-1" id="basic-slide-over-preview-<?php echo $job['pt_piece_id'];?>" aria-labelledby="basicSlideOver">
    <div class="offcanvas-header p-5">
        <h5 class="offcanvas-title fw-medium fs-base">
            <?php echo $job['od_customer_name'].' - '.$job['pt_piece_id'];?>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?php echo trad('estimated_time',$_SESSION['language']);?>: <b><?php echo $job['pt_expected_duration'];?></b><br>
        <br>
        <?php echo trad('customer',$_SESSION['language']);?>: <?php echo $job['od_customer_name'];?><br>
        <?php echo trad('csr',$_SESSION['language']);?>: <?php echo $job['od_csr_name'];?><br>
        <?php echo trad('order',$_SESSION['language']);?>: <?php echo $job['od_millnet_id'];?><br>
        <?php echo trad('piece',$_SESSION['language']);?>: <?php echo $job['pc_id'];?><br>
        <?php echo trad('rubber',$_SESSION['language']);?> : <?php echo $job['rb_label'];?><br>
        <?php echo trad('engraving_sleeve_length',$_SESSION['language']);?>: <?php echo $job['pc_sleeve_length'];?> mm<br>
        <?php echo trad('engraving_length',$_SESSION['language']);?>: <?php echo $job['pc_table_length'];?> mm<br>
        <?php echo trad('engraving_sleeve_offset',$_SESSION['language']);?>: <?php if($job['pc_sleeve_offset']=='0.00'){ echo trad('centered',$_SESSION['language']);} else { echo trad('decentralized',$_SESSION['language']);}?><br>
        <?php echo trad('mandrel_ø',$_SESSION['language']);?>: <?php echo $job['pc_mandrel_diameter'];?> mm<br>
        <?php echo trad('development',$_SESSION['language']);?>: <?php echo $job['pc_developement'];?> mm<br>
        <?php echo trad('grinding_ø',$_SESSION['language']);?>: <?php echo round($job['pc_developement']/pi(),2);?> mm<br>
        <?php echo trad('notch',$_SESSION['language']);?>: <?php echo $job['pc_notch_id'];?><br>
        <?php echo trad('notch_position',$_SESSION['language']);?>: <?php echo $job['pc_notch_position'];?><br>
        <?php echo trad('fiber',$_SESSION['language']);?>: <?php echo $job['fb_label'];?><br>
        <?php echo trad('fiber_thickness',$_SESSION['language']);?>: <?php echo $job['pc_fiber_thickness'];?> mm<br>
        <?php echo trad('chip',$_SESSION['language']);?>: <?php if($job['pc_chip'] ==0){ echo trad('without',$_SESSION['language']); } else { echo trad('with',$_SESSION['language']);} ?><br>
        <?php echo trad('cutback',$_SESSION['language']);?>: <?php if($job['pc_cutback'] ==0){ echo trad('without',$_SESSION['language']); } else { echo trad('with',$_SESSION['language']);} ?><br>
        <?php if($job['pc_cutback'] != 0){
            echo trad('cutback_diameter',$_SESSION['language']).': '.$job['pc_cutback_diameter'].' mm';
        }?><br>
    </div>
</div> 


