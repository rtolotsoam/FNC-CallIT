<?php
$style_border_categorie = array(
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => '000000'),
		),
	)
);
$style_font_inactif = array(
	'font' => array(
		/*'bold' => true,
		'size' => 9,
		'name' => 'Calibri',*/
		'color' => array('rgb'=> 'C71414')
	)
);
$style_font = array(
	'font' => array(
		'bold' => true,
		'size' => 9,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	)
);
$style_font_eval = array(
	'font' => array(
		'bold' => true,
		'size' => 12,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FFFFFF')
	)
);
$style_grand_titre = array(
	'font' => array(
		'bold' => true,
		'size' => 18,
		'name' => 'Calibri',
		'color' => array('rgb'=> '4F6228')
	)
);
$style_grand_titre_note = array(
	'font' => array(
		'bold' => true,
		'size' => 16,
		'name' => 'Calibri',
		'underline' => true,
		'color' => array('rgb'=> '376091')
	)
);
$style_grand_titre_eval = array(
	'font' => array(
		'bold' => true,
		'size' => 15,
		'name' => 'Calibri',
		'color' => array('rgb'=> '376091')
	)
);
$style_rotation = array(
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 90,
	    'wrap'     	 => true
	)
);
$style_centre = array(
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_titre = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'7699A3')
	)
);
$style_detail = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'C5D9F1')
	)
);
$style_detail_eval_date = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'95B3D7')
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	),
	'font' => array(
		'bold' => true,
		'size' => 12,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '7699A3'),
		),
	)
);
$style_detail_eval = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'BFBFBF')
	),
	'font' => array(
		'color' => array('rgb'=> '000000')
	)
);
$style_categorie = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID, 
		'color'=>array( 'rgb'=>'B1C6CB')
	)
);
$style_IS = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'A3D3FF')
	)
);
$style_matricule = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'BFBFBF')
	),
	'font' => array(
		'bold' => true,
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '7699A3'),
		),
	)
);
$style_CC = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'7F7F7F')
	),
	'font' => array(
		'bold' => true,
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FFFFFF')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => '7699A3'),
		),
	)
);
$style_note = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'E4E4E4')
	),
	'font' => array(
		'bold' => true,
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '376091')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => 'FFFFFF'),
		),
	)
);
$style_contenu_categorie = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'E4E4E4')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '376091')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => 'FFFFFF'),
		),
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_contenu_IS = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'CDCDCD')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '376091')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => 'FFFFFF'),
		),
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_contenu_detail = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'ECECEC')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '254061')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => 'FFFFFF'),
		),
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_contenu_detail_eval = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'F2F2F2')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '254061')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => 'FFFFFF'),
		),
	),
	'alignment' => array(
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_border = array(
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '7699A3'),
		),
	)
);
$style_total_titre = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'948B54')
	),
	'font' => array(
		'bold' => true,
		'size' => 12,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FFFFFF')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => '000000'),
		),
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_total_note = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'E4E4E4')
	),
	'font' => array(
		'bold' => true,
		'size' => 12,
		'name' => 'Calibri',
		'color' => array('rgb'=> '376091')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => '000000'),
		),
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_total_categorie = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'7F7F7F')
	),
	'font' => array(
		'bold' => true,
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FFFFFF')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => '000000'),
		),
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_total_detail = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'ECECEC')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '254061')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => '000000'),
		),
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_separateur = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'FFFFFF')
	)
);
$right = array(
	'alignment'=>array(
	'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
	'wrap'=>true			
	)
);
$left = array(
	'alignment'=>array(
	'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
	'wrap'=>true			
	)
);
$traitement = array(
	'1' => 'Appels Entrants',
	'2' => 'Appels Sortants',
	'3' => 'Traitement Mail',
	'4' => 'Traitement Tchat'
);
$traitement_abrev = array(
	'1' => 'AE',
	'2' => 'AS',
	'3' => 'MAIL',
	'4' => 'TCHAT'
);
$bordergras = array(
       'borders' => array(
       		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
             	),       
       ) 
);
$style_percent = array( 
	'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
);

$style_fourchette_date = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'FFE830')
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '000000'),
		)
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'wrap'     	 => true
	)
);

$style_fourchette_date_simple = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'FFE830')
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => 'FFFFFF'),
		)
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'wrap'     	 => true
	)
);


$font_calcul =  array(
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FFFFFF')
	)
);
/**
* *************************** TDB ************************************
*/
$tdb_style_type = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'ECECEC')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	),
	'borders' => array(
		'allborders' => array(
		'style'  => PHPExcel_Style_Border::BORDER_THIN,
		'color'  => array('rgb' => 'FFFFFF'),
		),
	)
);
$tdb_style_border_sep = array(
   'borders' => array(
        'allborders' => array(
			'style'  => array(
				'bottom' => PHPExcel_Style_Border::BORDER_THIN
				),
			'color'  => array('rgb' => '000000'),
		),   
   ) 
);
$tdb_style_centre = array(
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);

$style_minute_seconde = array( 
	'code' => PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4
);

$style_right = array(
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'rotation'   => 0
	)
);

$tdb_style_gras = array(
	'font' => array(
		'bold' => true
	)
);

$tdb_style_separateur = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'FFFFFF')
	),
	'font' => array(
		'bold' => true,
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	),
	'borders' => array(
		'allborders' => array(
			'style'  => PHPExcel_Style_Border::BORDER_THIN,
			'color'  => array('rgb' => '7699A3'),
			//'color'  => array('rgb' => 'FFFFFF'),
		),
	)
);

$tdb_style_eval = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'F2DDDC')
	),
	'font' => array(
		'bold' => true,
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FB0000')
	)
);

$style_total = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'F2DDDC')
	),
	'font' => array(
		'bold' => true,
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FB0000')
	)
);

$style_nom = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'F2DDDC')
	),
	'font' => array(
	
		'size' => 11,
		'name' => 'Calibri'
	)
);

$style_matricule = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'EAF2DD')
	),
	'font' => array(
	
		'size' => 11,
		'name' => 'Calibri'
	)
);

$style_nbEval = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'E6E0EC')
	),
	'font' => array(
		
		'size' => 11,
		'name' => 'Calibri'
	)
);

$style_prestation = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'DBEEF4')
	),
	'font' => array(
	
		'size' => 11,
		'name' => 'Calibri'
	)
);

$style_client = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'FCE9DA')
	),
	'font' => array(
	
		'size' => 11,
		'name' => 'Calibri'
	)
);

$style_date_eval = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'faeea8')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri'
	)
);

$style_tt = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array('rgb'=>'afdc7e')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri'
	)
);

$style_ligne_total = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'8AB5E7')
	),
	'font' => array(
	
		'size' => 13,
		'name' => 'Calibri'
	)
);

$style_ligne_date = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'0057A9')
	),
	'font' => array(
	
		'size' => 15,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'FFFFFF')
	),
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$center_align = array(	
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$right_align = array(	
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$center_align = array(	
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$left_align = array(	
	'alignment' => array(
	    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	    'rotation'   => 0,
	    'wrap'     	 => true
	)
);
$style_header = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'7699a3')
	),
	'font' => array(
		'size' => 12,
		'bold' => true,
		'name' => 'Calibri',
		'color' => array('rgb'=> 'ffffff')
	)
);
$style_sit_inac = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'a2d4e2')
	),
	'font' => array(
		'size' => 11,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	)
);
$style_pt_ap = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'afdc7e')
	),
	'font' => array(
		'size' => 11,
		'bold' => true,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	)
);
$style_axe_am = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'f9b551')
	),
	'font' => array(
		'size' => 11,
		'bold' => true,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	)
);
$style_prop_acc = array(
	'fill' => array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array( 'rgb'=>'a38ebc')
	),
	'font' => array(
		'size' => 11,
		'bold' => true,
		'name' => 'Calibri',
		'color' => array('rgb'=> '000000')
	)
);


$styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
  );
$grille_style_gras = array(
	'alignment' => array(
	    'wrap'     	 => true
	),
	'fill' => 
         array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => 
             array('rgb' => '8a024a') 
         ),
	'font' => array(
		'color' => array('rgb'=> 'FFFFFF'),
		'bold' => true
	)
);
$note_finale_style_gras = array(
	'alignment' => array(
	    'wrap'     	 => true
	),
	'fill' => 
         array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => 
             array('rgb' => 'ff99cc') 
         ),
	'font' => array(
		'color' => array('rgb'=> '000000'),
		'bold' => true
	)
);
$filtre_style = array(
	'alignment' => array(
	    'wrap'     	 => true
	),
	'fill' => 
         array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => 
             array('rgb' => 'f9f3f8') 
         ),
	'font' => array(
		'color' => array('rgb'=> '000000'),
		'bold' => false
	)
);
?>
