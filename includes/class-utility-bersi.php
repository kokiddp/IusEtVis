<?php

/**
 * Utilità generali
 *
 * @package Utility IusEtVis
 * @author  Andrea Bersi
 */
class Ius_Et_Vis_Util {

  public function __construct() {

	}

  public function export_csv($header_row,$data_rows,$filename=null){

    if(is_null($filename)) {
      $domain = $_SERVER['SERVER_NAME'];
      $filename = 'csv-' . $domain . '-' . time() . '.csv';
    }
    $fh = @fopen( 'php://output', 'w' );
    //fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
    fprintf( $fh );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' );
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    fputcsv( $fh, $header_row );
    foreach ( $data_rows as $data_row ) {
        fputcsv( $fh, $data_row );
    }
    fclose( $fh );

    ob_end_flush();

    die();
  }
}
