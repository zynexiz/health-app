<h2><?php echo _('Log data') ?></h2>
<p><i>
<?php
echo _('Leave password blank if you don\'t want to change it.');
?>
</i></p>

<?php
$sets = array(array(
	'label'=>'Set 1',
	'type'=>'bar',
	'data'=>[15,8,8,9,9,9,10,11,14,14,25]
),
array(
	'label'=>'Set 2',
	'type'=>'bar',
	'data'=>[1,48,28,13,19,29,5,9,19,23,17]
),
array(
	'label'=>'Set 2',
	'type'=>'line',
	'data'=>[1,48,28,13,19,29,5,9,19,23,17]
),
array(
	'label'=>'Set 2',
	'type'=>'bar',
	'data'=>[1,48,28,13,19,29,5,9,19,23,17]
),
array(
	'label'=>'Set 2',
	'type'=>'bar',
	'data'=>[1,48,28,13,19,29,5,9,19,23,17]
)
);
$labels = ['a','60','70','80','90','100','110','120','130','140','150'];
drawChart($labels,$sets);
?>
