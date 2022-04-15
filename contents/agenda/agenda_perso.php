
<?php
$list_fer=array(7);//Liste pour les jours ferié; EX: $list_fer=array(7,1)==>tous les dimanches et les Lundi seront des jours fériers
$list_spe=array('1986-10-31','2009-4-12','2009-9-23');//Mettez vos dates des evenements ; NB format(annee-m-j)
$lien_redir="date_info.php";//Lien de redirection apres un clic sur une date, NB la date selectionner va etre ajouter à ce lien afin de la récuperer ultérieurement 
$clic=1;//1==>Activer les clic sur tous les dates; 2==>Activer les clic uniquement sur les dates speciaux; 3==>Désactiver les clics sur tous les dates
$col1="rgba(0,51,102,0.4)";//couleur au passage du souris pour les dates normales
$col2="#8af5b5";//couleur au passage du souris pour les dates speciaux
$col3="#6a92db";//couleur au passage du souris pour les dates férié
if($_SESSION['language']=='fr'){
	$mois_fr = Array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août","Septembre", "Octobre", "Novembre", "Décembre");
} else{
	$mois_fr = Array("", "January", "February", "March", "April", "May", "June", "July", "August","September", "October", "November", "December");
}

if(isset($_GET['mois']) && isset($_GET['annee']))
{
	$mois=$_GET['mois'];
	$annee=$_GET['annee'];
}
else
{
	$mois=date("n");
	$annee=date("Y");
}
$ccl2=array($col1,$col2,$col3);
$l_day=date("t",mktime(0,0,0,$mois,1,$annee));
$x=date("N", mktime(0, 0, 0, $mois,1 , $annee));
$y=date("N", mktime(0, 0, 0, $mois,$l_day , $annee));
$titre=$mois_fr[$mois]." : ".$annee;
//echo $l_day;
?>

<center>
<form name="dt" method="get" action="">
<div class="form-inline">
		<select class="tom-select" name="mois" id="mois" onChange="change()" class="liste">
			<?php
				for($i=1;$i<13;$i++)
				{
					echo '<option value="'.$i.'"';
					if($i==$mois)
						echo ' selected ';
					echo '>'.$mois_fr[$i].'</option>';
				}
			?>
		</select>
		<select class="tom-select" name="annee" id="annee" onChange="change()" class="liste">
			<?php
				for($i=1950;$i<2035;$i++)
				{
					echo '<option value="'.$i.'"';
					if($i==$annee)
						echo ' selected ';
					echo '>'.$i.'</option>';
				}
			?>
		</select>
	</div>
</form>
<table class="tableau mt-6"><caption><?php echo $titre ;?></caption>
<?php
if($_SESSION['language']=='fr'){
	?>
	<tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr>
	<?php
} else{
	?>
	<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>
	<?php
}
?>
<tr>
<?php
//echo $y;
$case=0;
if($x>1)
	for($i=1;$i<$x;$i++)
	{
		echo '<td class="desactive">&nbsp;</td>';
		$case++;
	}
for($i=1;$i<($l_day+1);$i++)
{
	$f=$y=date("N", mktime(0, 0, 0, $mois,$i , $annee));
	if(strlen($mois)==1){
		$mois='0'.$mois;
	}
	if(strlen($i)==1){
		$i='0'.$i;
	}
	$date=$annee.'-'.$mois.'-'.$i;
	$da=$annee."-".$mois."-".$i;
	$lien=$lien_redir;
	$lien.="?dt=".$da;
	echo "<td";
	
	if(in_array($da, $list_spe))
	{
		echo " class='special' onmouseover='over(this,1,2)'";
		
	}
	else if(in_array($f, $list_fer))
	{
		echo " class='ferier' onmouseover='over(this,2,2)'";
		
	}
	else 
	{
		echo" onmouseover='over(this,0,2)' ";
		
	}
	echo" onmouseout='over(this,0,1)'><div class='case'><div class='agenda_num'>".$i.'</div>';
	$recipes=select_conges_acceptes_par_date($date);
		echo '<div class="contenu_agenda">';
		foreach($recipes as $recipe){
			$lien='visu_demande_conges.php?id='.$recipe['conges_id'];
			if($recipe['ID']==$_SESSION['ID']){
				if($recipe['conges_hours_day']==''){
					echo '<span style="color:#003366;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$recipe['conges_compte']."</a></span><br>";
				} else if ($recipe['conges_hours_start']>12 && $recipe['conges_hours_end']>=12){
					echo '<span style="color:#003366;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$recipe['conges_compte']."</a></span>(AM)<br>";
				} else if ($recipe['conges_hours_start']<=12 && $recipe['conges_hours_end']<12){
					echo '<span style="color:#003366;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$recipe['conges_compte']."</a></span>(PM)<br>";
				} else{
					echo '<span style="color:#003366;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$recipe['conges_compte']."</a></span>(Partiel)<br>";
				}
			}
		}
		$demandeeRecipes=select_conges_demandes_par_date($date);
		foreach($demandeeRecipes as $demandeeRecipe){
			if($recipe['ID']==$_SESSION['ID']){
				if($recipe['conges_hours_day']==''){
					echo '<span style="color:red;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$demandeeRecipe['conges_compte']."</a></span><br>";
				} else if ($recipe['conges_hours_start']>12 && $recipe['conges_hours_end']>=12){
					echo '<span style="color:red;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$demandeeRecipe['conges_compte']."</a>(AM)</span><br>";
				} else if ($recipe['conges_hours_start']<=12 && $recipe['conges_hours_end']<12){
					echo '<span style="color:red;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$demandeeRecipe['conges_compte']."</a>(PM)</span><br>";
				} else{
					echo '<span style="color:red;"><a onclick="ouvrirFenetre(\'visu_demande_conges.php?id='.$recipe['conges_id'].'\')">'.$demandeeRecipe['conges_compte']."</a>(Partiel)</span><br>";
				}
			}
		}
	echo '</div></div>';
	echo "</td>";
	$case++;
	if($case%7==0)
		echo "</tr><tr>";
	
}
if($y!=7)
	for($i=$y;$i<7;$i++)
	{
		echo '<td class="desactive">&nbsp;</td>';
	}
?></tr>
</table>
</center>

<script type="text/javascript">
function change()
{
	document.dt.submit();
}
	function over(this_,a,t)
{
	<?php 
	echo "var c2=['$ccl2[0]','$ccl2[1]','$ccl2[2]'];";
	?>
	var col;
	if(t==2)
		this_.style.backgroundColor=c2[a];
	else
		this_.style.backgroundColor="";
}
function go_lien(a)
{
	top.document.location=a;
}
</script>