<?php
/**
 * Composer
 */
 require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

/**
 * Utilità generali
 *
 * @package Utility IusEtVis
 * @author  Andrea Bersi
 */
class Ius_Et_Vis_Util {
  //istanza di logger
  protected $logger;

  public function __construct() {
    $this->logger = new Katzgrau\KLogger\Logger(get_home_path().'logs');
    $this->plugin_name = 'iusetvis';
	}

  /**
   * rimuove un elemento array da un array multidimensionale
   * @param  [type] $array [origine su cui oiperare]
   * @param  [type] $key   [chiave dell'array da eliminare]
   * @param  [type] $value [valore della chiave ]
   * @return [array]
   */
  public function remove_element_by_value($array, $key, $value){
    foreach($array as $subKey => $subArray){
        if($subArray[$key] == $value){
             unset($array[$subKey]);
        }
    }
    //$this->logger->debug("passato $key e $value",$array);

    return $array;
  }

  /**
   * esporta un csv
   * @param  [type] $header_row intestazione dei campi
   * @param  [type] $data_rows  data da scrivere
   * @param  [type] $filename   nome del file
   * @return [type]             [description]
   */
  public function export_csv( $header_row , $data_rows , $filename=null ){

    if(is_null($filename)) {
      $domain = $_SERVER['SERVER_NAME'];
      $filename = 'csv-' . $domain . '-' . time() . '.csv';
    }
    $this->logger->info("Esportazione file CSV $filename");
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

  /**
   * [send_email_subscribed description]
   * @param  integer $course_id [description]
   * @return [type]             [description]
   */
  public function send_email_subscribed( $course_id = 0 ){

    //echo (($course_id));die();
    $options = get_option( $this->plugin_name . '_settings' );
    $text = ! isset( $options['iusetvis_email_course_ended'] ) ? __("We would like to inform you the conclusive documentation of the course mentioned in the subject is now available on iusetvis.it in the section Personal space",'ius') : $options['iusetvis_email_course_ended'];

    $course_meta = get_post_custom( $course_id );
    $subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );

    //dati email
    $headers =  array();
    //$to = "segreteria@iusetvis.it";
    $headers[] = 'From: Ius et Vis <info@iusetvis.it>';
    $title_course = get_the_title($course_id);
    foreach ( $subscribed_users as $key => $value ) {
        $user_id = $value;
        $user_data = get_userdata( $user_id );
        //$headers[] = "Bcc: {$user_data->user_email}";
        $esito = wp_mail( $user_data->user_email, $title_course, $text );
        if( $esito ) {
          $this->logger->info("Invio email termine corso $title_course => {$user_data->user_email}");
        } else {
          $this->logger->error("Invio email termine corso $title_course => {$user_data->user_email}");
        }
    }
    return;
  }

  /**
   * restituisce true se il corso è chiuso
   * @param  integer $course_id [id del corso]
   * @return boolean true se chiuso
  */
  public function is_course_closed ( $course_id = 0 ){
		if ( $course_id == '0' ) {
			echo __( 'Error: course unset!', 'iusetvis' );
			die();
		}

    $course_meta = get_post_custom( $course_id );
    $is_course_closed = !isset( $course_meta['course_ended'][0] ) ? false : (int)( $course_meta['course_ended'][0] );
		return $is_course_closed;
  }

}
