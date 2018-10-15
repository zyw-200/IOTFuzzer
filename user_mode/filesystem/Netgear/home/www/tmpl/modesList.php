<?php

$numModes0 = sizeof($this->_tpl_vars['ModeList0']);
$numModes1 = sizeof($this->_tpl_vars['ModeList1']);
$modes0='';
$modes1='';
$count0 = 1;
$count1 = 1;

for($i=0; $i < $numModes0; $i++){
        $modes0.=$this->_tpl_vars['ModeList0'][$i];
        if($count0 < $numModes0){
        $modes0.=",";
                $count0++;
        }
}

if($numModes0 >0 && $numModes1 >0){
        $modes1=',';
}

for($i=0; $i < $numModes1; $i++){
        $modes1.=$this->_tpl_vars['ModeList1'][$i];
        if($count1 < $numModes1){
                $modes1.=",";
                $count1++;
        }
}
?>

<script language="javascript">
        numModes0 ="<?php  echo $numModes0; ?>";
        modesList0 ="<?php echo $modes0; ?>";
        numModes1 ="<?php echo $numModes1; ?>";
        modesList1 ="<?php echo $modes1; ?>";
        modesList="<?php echo $modes0.$modes1; ?>";
</script>

