<?php /**
 * @version 1.1
 * @package Booking Calendar
 * @category Timeline for Admin Panel
 * @author wpdevelop
 *
 * @web-site https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com 
 * 
 * @modified 2016-01-18
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


class WPBC_Timeline {
     
    
    public $bookings;       // Booking objects from external function
    public $booking_types;  // Resources objects from external function
        
    public $dates_array;    // Dates for Timeline format
    public $time_array_new; // Times for Timeline format
        
    public $request_args;   // Parsed paramaters

    private $is_frontend;   // Client ot Admin  sides.
    
    public $timeline_titles;
    
    private $week_days_titles;
    private $current_user_id;
    
    private $html_client_id;        // ID of border element at  client side.
    public $options;                                                            //FixIn:7.0.1.50
    
    public function __construct(){// $bookings, $booking_types ) {
        
        $this->options = array();                                               //FixIn:7.0.1.50
        
        $this->html_client_id = false;
        
        $this->current_user_id = 0;
        
        $this->is_frontend = false;

	    //FixIn: 8.1.3.31
        $calendar_overview_start_time = get_bk_option( 'booking_calendar_overview_start_time' );
        $calendar_overview_end_time   = get_bk_option( 'booking_calendar_overview_end_time' );
		$hours_limit = ( empty( $calendar_overview_start_time ) ? '0' : $calendar_overview_start_time )
					   . ','
                       . ( empty( $calendar_overview_end_time ) ? '24' : $calendar_overview_end_time );
        $this->request_args = array(                                     
                                      'wh_booking_type' => '1'            
                                    , 'is_matrix' => false
                                    , 'view_days_num' => '90'
                                    , 'scroll_start_date' => ''
                                    , 'scroll_day' => 0
                                    , 'scroll_month' => 0
                                    , 'wh_trash' => ''    
                                    , 'limit_hours' => $hours_limit        									// '0,24'      //FixIn: 7.0.1.14  if ( ! ( ( $tt >= $start_hour_for_1day_view ) && ( $tt <= $end_hour_for_1day_view ) ) ) continue;
                                    , 'only_booked_resources' => ( isset( $_REQUEST['only_booked_resources'] ) ) ? 1 : 0              //FixIn: 7.0.1.51
                                    , 'booking_hash' => ( isset( $_REQUEST['booking_hash'] ) ) ? $_REQUEST['booking_hash'] : ''              //FixIn: 8.1.3.5

        ); 
        
        $this->timeline_titles = array( 
                                    'header_column1' => __('Resources', 'booking')
                                    , 'header_column2' => __('Dates', 'booking')
                                    , 'header_title' => __('Bookings', 'booking')
                                );
        
        
        $this->week_days_titles = array(
                                        'full' => array( 
                                              1 => __( 'Monday', 'booking' )
                                            , 2 => __( 'Tuesday', 'booking' )
                                            , 3 => __( 'Wednesday', 'booking' )
                                            , 4 => __( 'Thursday', 'booking' )
                                            , 5 => __( 'Friday', 'booking' )
                                            , 6 => __( 'Saturday', 'booking' )
                                            , 7 => __( 'Sunday', 'booking' ) 
                                            )
                                        , '3' => array(                         //FixIn: 7.0.1.11
                                              1 =>  __( 'Mon', 'booking' )
                                            , 2 =>  __( 'Tue', 'booking' )
                                            , 3 =>  __( 'Wed', 'booking' )
                                            , 4 =>  __( 'Thu', 'booking' )
                                            , 5 =>  __( 'Fri', 'booking' )
                                            , 6 =>  __( 'Sat', 'booking' )
                                            , 7 =>  __( 'Sun', 'booking' )
                                            )
                                        , '1' => array(                         //FixIn: 7.0.1.11
                                              1 => substr( __( 'Mon', 'booking' ), 0, -1 )
                                            , 2 => substr( __( 'Tue', 'booking' ), 0, -1 )
                                            , 3 => substr( __( 'Wed', 'booking' ), 0, -1 )
                                            , 4 => substr( __( 'Thu', 'booking' ), 0, -1 )
                                            , 5 => substr( __( 'Fri', 'booking' ), 0, -1 )
                                            , 6 => substr( __( 'Sat', 'booking' ), 0, -1 )
                                            , 7 => substr( __( 'Sun', 'booking' ), 0, -1 ) 
                                            )
                                    );
        
        
    }
    
    ////////////////////////////////////////////////////////////////////////////

    /**
	 * Init Timeline From page shortcode
     * 
     * @param array $attr = array(                                     
                                      'wh_booking_type' => ''            
                                    , 'is_matrix' => false
                                    , 'view_days_num' => '30'
                                    , 'scroll_start_date' => ''
                                    , 'scroll_day' => 0
                                    , 'scroll_month' => 0
                                );        
     */
    public function client_init( $attr ) {

        $this->is_frontend = true;

        //FixIn:7.0.1.50
        if ( isset( $attr['options'] ) ) {

            $bk_otions = $attr['options'];
            $custom_params = array();
            if (! empty($bk_otions)) {
                $param ='\s*([^\s]+)=[\'"]{1}([^\'"]+)[\'"]{1}\s*';      // Find all possible options
                $pattern_to_search='%\s*{([^\s]+)' . $param .'}\s*[,]?\s*%';
                preg_match_all($pattern_to_search, $bk_otions, $matches, PREG_SET_ORDER);
                //debuge($matches);
                /**
	 * [bookingtimeline  ... options='{resource_link 3="http://beta/resource-apartment3-id3/"},{resource_link 4="http://beta/resource-3-id4/"}' ... ]
                    [0] => {resource_link 3="http://beta/resource-apartment3-id3/"},
                    [1] => resource_link                                        // Name
                    [2] => 3                                                    // ID
                    [3] => http://beta/resource-apartment3-id3/                 // Value
                 */
                foreach ( $matches as $matche_value ) {

                    if ( ! isset( $this->options[ $matche_value[1] ] ) ) {
                        $this->options[ $matche_value[1] ] = array();
                    }
                    $this->options[ $matche_value[1] ][ $matche_value[2] ] = $matche_value[3];
                }
            }

//debuge($this->options);
        }
        //FixIn:7.0.1.50


        //Ovverride some parameters
        if ( isset( $attr['type'] ) ) {
            $attr['wh_booking_type'] = $attr['type'];                           //Instead of 'wh_booking_type' paramter  in shortcode is used 'type' parameter
        }

        // Get paramaters from shortcode paramaters
        $this->define_request_view_params_from_params( $attr );

        if ( ! $this->request_args['is_matrix'] )
            $this->timeline_titles['header_column1'] = '';

        //Override any possible titles from shortcode paramaters
        $this->timeline_titles = wp_parse_args( $attr, $this->timeline_titles );

        // Get clean parameters to  request booking data
        $args = $this->wpbc_get_clean_paramas_from_request_for_timeline();


		//FixIn: 8.1.3.5
		/**	Client - Page first load
		 *
		 * If provided valid request_args['booking_hash']
		 *		- Firstly  defined in constructor in $_REQUEST['booking_hash']
		 * 		- or overwrited in 		define_request_view_params_from_params		from  parameters in shortcode 'booking_hash'
		 * then check, if exist booking for this hash.
		 * If exist, get Email of this booking,  and
		 * filter getting all  other bookings by email keyword.
		 * Addtionly set param ['only_booked_resources'] for showing only booking resources with  exist bookings.
		 */
		if ( isset( $this->request_args['booking_hash'] ) ) {

			// Get booking details by HASH,  and then  return Email (or other data of booking,  or false if error
			$booking_details_email = wpbc_check_hash_get_booking_details( $this->request_args['booking_hash'] , 'email' );

			if ( ! empty( $booking_details_email ) ) {

				// Do  not show booking resources with  no bookings
				$this->request_args['only_booked_resources'] = 1;

				//Set keyword for showing bookings ony  relative to this email
				$args['wh_keyword'] = $booking_details_email;															// 'jo@wpbookingcalendar.com';
			}
		}
		//FixIn: 8.1.3.5 	-	End


        // Get booking data
        $bk_listing = wpbc_get_bookings_objects( $args );   
        $this->bookings = $bk_listing['bookings'];
        $this->booking_types = $bk_listing['resources'];

        //Get Dates and Times for Timeline format
//debuge($this->bookings[84]);        
        $bookings_date_time = $this->wpbc_get_dates_and_times_for_timeline( $this->bookings );
        $this->dates_array = $bookings_date_time[0];
        $this->time_array_new = $bookings_date_time[1];
//debuge($this->time_array_new['2017-01-13']);


        //$milliseconds = round(microtime(true) * 1000);                       //FixIn: 7.0.Beta.18
        $milliseconds = rand( 10000, 99999 );

        $this->html_client_id = 'wpbc_timeline_' . $milliseconds;

        return $this->html_client_id;
    }



    /**
	 * Init parameters after Ajax Navigation actions
     * 
     * @param array $attr
     * @return string html_client_id - exist  from input parameters
     */
    public function ajax_init( $attr ) {

        $this->is_frontend = (bool) $attr['is_frontend'];;

        //Ovverride some parameters
        if ( isset( $attr['type'] ) ) {
            $attr['wh_booking_type'] = $attr['type'];                           //Instead of 'wh_booking_type' paramter  in shortcode is used 'type' parameter
        }

        // Get paramaters from shortcode paramaters
        $this->define_request_view_params_from_params( $attr );


        if ( ! $this->request_args['is_matrix'] ) {

            switch ( $this->request_args['view_days_num'] ) {
                case '90':
                case '30':
                    if ( isset( $this->request_args['scroll_day'] ) ) $scroll_day = intval( $this->request_args['scroll_day'] );
                    else                                              $scroll_day = 0;

                    if ( $attr['nav_step'] == '-1' )    $this->request_args['scroll_day'] = intval( $scroll_day - 7 );
                    if ( $attr['nav_step'] == '1' )     $this->request_args['scroll_day'] = intval( $scroll_day + 7 );

                    /*
                    $scroll_params = array( '&scroll_day='.intval($scroll_day-4*7),
                                            '&scroll_day='.intval($scroll_day-7),
                                            '&scroll_day=0',
                                            '&scroll_day='.intval($scroll_day+7 ),
                                            '&scroll_day='.intval($scroll_day+4*7) );
                    $scroll_titles = array(  __('Previous 4 weeks' ,'booking'),
                                             __('Previous week' ,'booking'),
                                             __('Current week' ,'booking'),
                                             __('Next week' ,'booking'),
                                             __('Next 4 weeks' ,'booking') ); */
                    break;
                default:  // 365
                    if ( !isset( $this->request_args['scroll_month'] ) )    $this->request_args['scroll_month'] = 0;
                    $scroll_month = intval( $this->request_args['scroll_month'] );

                    if ( $attr['nav_step'] == '-1' )    $this->request_args['scroll_month'] = intval( $scroll_month - 1 );
                    if ( $attr['nav_step'] == '1' )     $this->request_args['scroll_month'] = intval( $scroll_month + 1 );
                    /*
                    $scroll_params = array( '&scroll_month='.intval($scroll_month-3),
                                            '&scroll_month='.intval($scroll_month-1),
                                            '&scroll_month=0',
                                            '&scroll_month='.intval($scroll_month+1 ),
                                            '&scroll_month='.intval($scroll_month+3) );
                    $scroll_titles = array(  __('Previous 3 months' ,'booking'),
                                             __('Previous month' ,'booking'),
                                             __('Current month' ,'booking'),
                                             __('Next month' ,'booking'),
                                             __('Next 3 months' ,'booking') );*/
                    break;
            }
        } else { // Matrix

            switch ( $this->request_args['view_days_num'] ) {
                case '1': //Day
                    if ( isset( $this->request_args['scroll_day'] ) )   $scroll_day = intval( $this->request_args['scroll_day'] );
                    else                                                $scroll_day = 0;

                    if ( $attr['nav_step'] == '-1' )    $this->request_args['scroll_day'] = intval( $scroll_day - 1 );
                    if ( $attr['nav_step'] == '1' )     $this->request_args['scroll_day'] = intval( $scroll_day + 1 );
                    /*
                    $scroll_params = array( '&scroll_day='.intval($scroll_day-7),
                                            '&scroll_day='.intval($scroll_day-1),
                                            '&scroll_day=0',
                                            '&scroll_day='.intval($scroll_day+1 ),
                                            '&scroll_day='.intval($scroll_day+7) );
                    $scroll_titles = array(  __('Previous 7 days' ,'booking'),
                                             __('Previous day' ,'booking'),
                                             __('Current day' ,'booking'),
                                             __('Next day' ,'booking'),
                                             __('Next 7 days' ,'booking') );*/
                    break;

                case '7': //Week

                    if ( isset( $this->request_args['scroll_day'] ) )   $scroll_day = intval( $this->request_args['scroll_day'] );
                    else                                                $scroll_day = 0;

                    if ( $attr['nav_step'] == '-1' )    $this->request_args['scroll_day'] = intval( $scroll_day - 7 );
                    if ( $attr['nav_step'] == '1' )     $this->request_args['scroll_day'] = intval( $scroll_day + 7 );
                    /*
                    $scroll_params = array( '&scroll_day='.intval($scroll_day-4*7),
                                            '&scroll_day='.intval($scroll_day-7),
                                            '&scroll_day=0',
                                            '&scroll_day='.intval($scroll_day+7 ),
                                            '&scroll_day='.intval($scroll_day+4*7) );
                    $scroll_titles = array(  __('Previous 4 weeks' ,'booking'),
                                             __('Previous week' ,'booking'),
                                             __('Current week' ,'booking'),
                                             __('Next week' ,'booking'),
                                             __('Next 4 weeks' ,'booking') );*/
                    break;

                case '30':
                case '60':
                case '90': //3 months

                    if ( !isset( $this->request_args['scroll_month'] ) )    $this->request_args['scroll_month'] = 0;
                    $scroll_month = intval( $this->request_args['scroll_month'] );

                    if ( $attr['nav_step'] == '-1' )    $this->request_args['scroll_month'] = intval( $scroll_month - 1 );
                    if ( $attr['nav_step'] == '1' )     $this->request_args['scroll_month'] = intval( $scroll_month + 1 );
                    /*
                    $scroll_params = array( '&scroll_month='.intval($scroll_month-3),
                                            '&scroll_month='.intval($scroll_month-1),
                                            '&scroll_month=0',
                                            '&scroll_month='.intval($scroll_month+1 ),
                                            '&scroll_month='.intval($scroll_month+3) );
                    $scroll_titles = array(  __('Previous 3 months' ,'booking'),
                                             __('Previous month' ,'booking'),
                                             __('Current month' ,'booking'),
                                             __('Next month' ,'booking'),
                                             __('Next 3 months' ,'booking') );*/
                    break;

                default:  // 30, 60, 90...
                    if ( !isset( $this->request_args['scroll_month'] ) )    $this->request_args['scroll_month'] = 0;
                    $scroll_month = intval( $this->request_args['scroll_month'] );

                    if ( $attr['nav_step'] == '-1' )    $this->request_args['scroll_month'] = intval( $scroll_month - 1 );
                    if ( $attr['nav_step'] == '1' )     $this->request_args['scroll_month'] = intval( $scroll_month + 1 );
                    /*
                    $scroll_params = array( '&scroll_month='.intval($scroll_month-3),
                                            '&scroll_month='.intval($scroll_month-1),
                                            '&scroll_month=0',
                                            '&scroll_month='.intval($scroll_month+1 ),
                                            '&scroll_month='.intval($scroll_month+3) );
                    $scroll_titles = array(  __('Previous 3 months' ,'booking'),
                                             __('Previous month' ,'booking'),
                                             __('Current month' ,'booking'),
                                             __('Next month' ,'booking'),
                                             __('Next 3 months' ,'booking') );
                     */
                    break;
            }
        }

                // Titles
                if ( ! $this->request_args['is_matrix'] )
                    $this->timeline_titles['header_column1'] = '';

                //Override any possible titles from shortcode paramaters
                $this->timeline_titles = wp_parse_args( $attr, $this->timeline_titles );


        // Get clean parameters to  request booking data
        $args = $this->wpbc_get_clean_paramas_from_request_for_timeline();


		//FixIn: 8.1.3.5
	    /**
	     * If provided valid ['booking_hash'] in timeline_obj in JavaScript param during Ajax request,
	     * then check, if exist booking for this hash. If exist, get Email of this booking,  and
	     * filter getting all  other bookings by email keyword.
		 * Addtionly set param ['only_booked_resources'] for showing only booking resources with  exist bookings
	     */
		if ( isset( $attr['booking_hash'] ) ) {

			// Get booking details by HASH,  and then  return Email (or other data of booking,  or false if error
			$booking_details_email = wpbc_check_hash_get_booking_details( $attr['booking_hash'] , 'email' );

			if ( ! empty( $booking_details_email ) ) {

				// Do  not show booking resources with  no bookings
				$this->request_args['only_booked_resources'] = 1;

				//Set keyword for showing bookings ony  relative to this email
				$args['wh_keyword'] = $booking_details_email;															// 'jo@wpbookingcalendar.com';
			}
		}
		//FixIn: 8.1.3.5 	-	End


        // Get booking data
        $bk_listing = wpbc_get_bookings_objects( $args );   

        $this->bookings = $bk_listing['bookings'];
        $this->booking_types = $bk_listing['resources'];

        //Get Dates and Times for Timeline format        
        $bookings_date_time = $this->wpbc_get_dates_and_times_for_timeline( $this->bookings );
        $this->dates_array = $bookings_date_time[0];
        $this->time_array_new = $bookings_date_time[1];
                
    
        $this->html_client_id = $attr['html_client_id'];

        return $this->html_client_id;
    }
    
    
    /** Define initial REQUEST parameters for Admin Panel  and Get bookings and resources */
    public function admin_init() {
        
        // User ////////////////////////////////////////////////////////////////
        $user = wp_get_current_user();
        $this->current_user_id = $user->ID;
        
        $this->is_frontend = false;
        
        // Get paramaters from REQUEST
        $this->define_request_view_params();
        
        if ( ! $this->request_args['is_matrix'] )
            $this->timeline_titles['header_column1'] = '';
        
// debuge($this->request_args);

        // Get clean parameters to  request booking data
        $args = $this->wpbc_get_clean_paramas_from_request_for_timeline();

        // Get booking data
        $bk_listing = wpbc_get_bookings_objects( $args );
        $this->bookings = $bk_listing['bookings'];
        $this->booking_types = $bk_listing['resources'];

        //Get Dates and Times for Timeline format
        $bookings_date_time = $this->wpbc_get_dates_and_times_for_timeline( $this->bookings );
        $this->dates_array = $bookings_date_time[0];
        $this->time_array_new = $bookings_date_time[1];
    }
    
    
    public function client_navigation( $param ) {		
        ?>
        <script type="text/javascript">
            wpbc_timeline_obj["<?php echo  $this->html_client_id; ?>"] = {     
                                        is_frontend: "<?php       echo  ( $this->is_frontend ? '1' : '0' ); ?>"
                                        , html_client_id: "<?php    echo  $this->html_client_id; ?>"
                                        , wh_booking_type: "<?php   echo  $this->request_args['wh_booking_type']; ?>"
                                        , is_matrix: "<?php         echo  ( $this->request_args['is_matrix'] ? '1' : '0' ); ?>"
                                        , view_days_num: "<?php     echo  $this->request_args['view_days_num']; ?>"
                                        , scroll_start_date: "<?php echo  $this->request_args['scroll_start_date']; ?>"
                                        , scroll_day: "<?php        echo  $this->request_args['scroll_day']; ?>"
                                        , scroll_month: "<?php      echo  $this->request_args['scroll_month']; ?>"
                                      , 'header_column1': "<?php    echo esc_js( $this->timeline_titles['header_column1'] ); ?>"
                                      , 'header_column2': "<?php    echo esc_js( $this->timeline_titles['header_column2'] ); ?>"
                                      , 'header_title': "<?php      echo esc_js( $this->timeline_titles['header_title'] ); ?>"
                                      , 'wh_trash': "<?php          echo esc_js( $this->request_args['wh_trash'] ); ?>"
                                      , 'limit_hours': "<?php           echo esc_js( $this->request_args['limit_hours'] ); ?>"                //FixIn: 7.0.1.14
                                      , 'only_booked_resources': "<?php echo esc_js( $this->request_args['only_booked_resources'] ); ?>"      //FixIn: 7.0.1.51
									  , 'options': '<?php echo  maybe_serialize( $this->options ) ; ?>'			//FixIn: 7.2.1.14
				  				      , 'booking_hash': "<?php          echo esc_js( $this->request_args['booking_hash'] ); ?>"				//FixIn: 8.1.3.5
                                    };
        </script>
        <div class="wpbc_tl_nav">
            <div class="wpbc_tl_prev" href="javascript:void(0)" onclick="javascript:wpbc_timeline_nav( wpbc_timeline_obj['<?php echo  $this->html_client_id; ?>'], -1 );"><a>&laquo;</a></div>
            <div class="wpbc_tl_title"><?php echo $param['title'] ?></div>
            <div class="wpbc_tl_next" href="javascript:void(0)" onclick="javascript:wpbc_timeline_nav( wpbc_timeline_obj['<?php echo  $this->html_client_id; ?>'],  1 );"><a>&raquo;</a></div>
        </div>
       <?php 
    }
    
    ////////////////////////////////////////////////////////////////////////////
    //  S u p p o r t
    ////////////////////////////////////////////////////////////////////////////        

    /**
	 * Get array of cleaned (limited number) paramas from request for getting bookings by "wpbc_get_bookings_objects"
     * 
     * @return array
     */
    public function wpbc_get_clean_paramas_from_request_for_timeline() {

        //FixIn: 7.0.1.15       -   replacing in this file from date( to  date_i18n(
        $start_year  = intval( date_i18n( "Y" ) ); 
        $start_month = intval( date_i18n( "m" ) );
        $start_day = 1;
//debuge( '1.( $start_year, $start_month, $start_day , $this->request_args ',  $start_year, $start_month, $start_day , $this->request_args );        
        if ( ! empty( $this->request_args['scroll_start_date'] ) ) {            // scroll_start_date=2013-07-01
            
            list( $start_year, $start_month, $start_day ) = explode( '-', $this->request_args['scroll_start_date'] );
            $start_year  = intval( $start_year );
            $start_month = intval( $start_month );            
            $start_day   = intval( $start_day );                    
        }
//debuge( '2.( $start_year, $start_month, $start_day )',  $start_year, $start_month, $start_day );
        $scroll_day = 0;
        $scroll_month = 0;

        if ( ( isset( $this->request_args['view_days_num'] ) ) 
            //&& ($this->request_args['view_days_num'] != '30') 
            )
            $view_days_num = $this->request_args['view_days_num'];
        else
            $view_days_num = get_bk_option( 'booking_view_days_num' );

        $view_days_num = intval( $view_days_num );
//debuge( '2.1( $view_days_num )', $view_days_num );        
        $is_matrix = (bool) $this->request_args['is_matrix'];

        if ( $is_matrix ) {

            switch ( $view_days_num ) {

                case '1':
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = intval( date_i18n( "d" ) );                               // Today date

                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = intval( $this->request_args['scroll_day'] );

                    $real_date = mktime( 0, 0, 0, $start_month, ($start_day + $scroll_day ), $start_year );
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );

                    $real_date = mktime( 0, 0, 0, $start_month, ($start_day + 0 + $scroll_day ), $start_year );
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );
                    break;

                case '7':
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = intval( date_i18n( "d" ) );   //Today  date
                    $start_week_day_num = intval( date_i18n( "w" ) );
                    $start_day_weeek = intval( get_bk_option( 'booking_start_day_weeek' ) ); //[0]:Sun .. [6]:Sut
                    if ( $start_week_day_num != $start_day_weeek ) {
                        for ( $d_inc = 1; $d_inc < 8; $d_inc++ ) {              // Just get week  back
                            $real_date = mktime( 0, 0, 0, $start_month, ($start_day - $d_inc ), $start_year );
                            $start_week_day_num = intval( date_i18n( "w", $real_date ) );
                            if ( $start_week_day_num == $start_day_weeek ) {
                                $start_day   = intval( date_i18n( "d", $real_date ) );
                                $start_year  = intval( date_i18n( "Y", $real_date ) );
                                $start_month = intval( date_i18n( "m", $real_date ) );
                                $d_inc = 9;
                            }
                        }
                    }

                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = intval( $this->request_args['scroll_day'] );

                    $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $scroll_day ), $start_year );
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );

                    $real_date = mktime( 0, 0, 0, $start_month, ($start_day + 7 + $scroll_day ), $start_year );
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );
                    break;

                case '30':
                    if ( isset( $this->request_args['scroll_month'] ) )
                        $scroll_month = intval( $this->request_args['scroll_month'] );

//debuge('3.$scroll_month, $start_month, $start_day, $start_year', $scroll_month, $start_month, $start_day, $start_year );
                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), ( $start_day ), $start_year );
//debuge('4.$real_date',$real_date);
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );
//debuge('5.$wh_booking_date',$wh_booking_date);                    
                    $real_date = mktime( 0, 0, 0, ($start_month + 1 + $scroll_month ), ($start_day - 1 ), $start_year );
//debuge('6.$real_date',$real_date);         
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );
//debuge('7.$wh_booking_date2', $wh_booking_date2);                    
                    break;

                case '60':
                    if ( isset( $this->request_args['scroll_month'] ) )
                        $scroll_month = intval( $this->request_args['scroll_month'] );

                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), ( $start_day ), $start_year );
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );                          // '2012-12-01';

                    $real_date = mktime( 0, 0, 0, ($start_month + 2 + $scroll_month ), ($start_day - 1 ), $start_year );
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );                          // '2013-02-31';                    
                    break;

                ////////////////////////////////////////////////////////////////////////////////
                default:  // 30 - default
                    if ( isset( $this->request_args['scroll_month'] ) )
                        $scroll_month = intval( $this->request_args['scroll_month'] );

                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), ( $start_day ), $start_year );
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );                          // '2012-12-01';

                    $real_date = mktime( 0, 0, 0, ($start_month + 1 + $scroll_month ), ($start_day - 1 ), $start_year );
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );                          // '2012-12-31';
                    break;
            }
            
        } else {   // Single resource
            
            switch ( $view_days_num ) {
                
                case '90':

                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = intval( date_i18n( "d" ) );    //Today Date
                    $start_week_day_num = intval( date_i18n( "w" ) );
                    $start_day_weeek = intval( get_bk_option( 'booking_start_day_weeek' ) ); //[0]:Sun .. [6]:Sut

                    if ( $start_week_day_num != $start_day_weeek ) {
                        for ( $d_inc = 1; $d_inc < 8; $d_inc++ ) {              // Just get week  back
                            $real_date = mktime( 0, 0, 0, $start_month, ($start_day - $d_inc ), $start_year );
                            $start_week_day_num = intval( date_i18n( "w", $real_date ) );
                            if ( $start_week_day_num == $start_day_weeek ) {
                                $start_day   = intval( date_i18n( "d", $real_date ) );
                                $start_year  = intval( date_i18n( "Y", $real_date ) );
                                $start_month = intval( date_i18n( "m", $real_date ) );
                                $d_inc = 9;
                            }
                        }
                    }

                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = intval( $this->request_args['scroll_day'] );

                    $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $scroll_day ), $start_year );
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );                          // '2012-12-01';

                    $real_date = mktime( 0, 0, 0, $start_month, ($start_day + 7 * 12 + 7 + $scroll_day ), $start_year );
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );                          // '2013-12-31';
                    break;

                case '30':
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = intval( date_i18n( "d" ) );    //Today Date

                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = intval( $this->request_args['scroll_day'] );

                    $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $scroll_day ), $start_year );
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );                          // '2012-12-01';

                    $real_date = mktime( 0, 0, 0, $start_month, ($start_day + 31 + $scroll_day ), $start_year );
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );                          // '2013-12-31';
                    break;

                default:  // 365

                    if ( isset( $this->request_args['scroll_month'] ) )
                        $scroll_month = intval( $this->request_args['scroll_month'] );
                    else
                        $scroll_month = 0;

                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), $start_day, $start_year );
                    $wh_booking_date = date_i18n( "Y-m-d", $real_date );                          // '2012-12-01';

                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month + 13 ), ($start_day - 1 ), $start_year );
                    $wh_booking_date2 = date_i18n( "Y-m-d", $real_date );                          // '2013-12-31';

                    break;
            }
        }

        $or_sort = get_bk_option( 'booking_sort_order' );

        $args = array(
            'wh_booking_type' => $this->request_args['wh_booking_type'],
            'wh_approved' => '',
            'wh_booking_id' => '',
            'wh_is_new' => '',
            'wh_pay_status' => 'all',
            'wh_keyword' => '',
            'wh_booking_date' => $wh_booking_date,
            'wh_booking_date2' => $wh_booking_date2,
            'wh_modification_date' => '3',
            'wh_modification_date2' => '',
            'wh_cost' => '',
            'wh_cost2' => '',
            'or_sort' => $or_sort,
            'page_num' => '1',
            'wh_trash' => $this->request_args['wh_trash'], 
            'limit_hours' => $this->request_args['limit_hours'], 
            'only_booked_resources' => $this->request_args['only_booked_resources'],    //FixIn: 7.0.1.51
            'page_items_count' => '100000'
        );
//debuge('8.',$args);
        return $args;
    }

    
    /** Define View Params from  $_REQUEST */
    public function define_request_view_params() {
        
        if ( isset( $_REQUEST['wh_booking_type'] ) ) {                          
                                                        $this->request_args['wh_booking_type'] = $_REQUEST['wh_booking_type'];          // Used once for comma seperated resources only.            
        } elseif ( isset( $_GET['booking_type'] ) ) {   $this->request_args['wh_booking_type'] = $_GET['booking_type'];
        } 
        
        if (  ( isset( $_REQUEST['wh_booking_type'] ) ) && ( strpos( $_REQUEST['wh_booking_type'], ',' ) !== false )  ) 
                                                        $this->request_args['is_matrix'] = true;                    
        if ( isset( $_REQUEST['view_days_num'] ) )      $this->request_args['view_days_num'] = $_REQUEST['view_days_num'];        
        if ( isset( $_REQUEST['scroll_start_date'] ) )  $this->request_args['scroll_start_date'] = $_REQUEST['scroll_start_date'];                
        if ( isset( $_REQUEST['scroll_day'] ) )         $this->request_args['scroll_day'] = $_REQUEST['scroll_day'];        
        if ( isset( $_REQUEST['scroll_month'] ) )       $this->request_args['scroll_month'] = $_REQUEST['scroll_month'];        
        if ( isset( $_REQUEST['wh_trash'] ) )           $this->request_args['wh_trash'] = $_REQUEST['wh_trash'];
        
        if ( isset( $_REQUEST['limit_hours'] ) )            $this->request_args['limit_hours'] = $_REQUEST['limit_hours'];                      //FixIn: 7.0.1.14
        if ( isset( $_REQUEST['only_booked_resources'] ) )  $this->request_args['only_booked_resources'] = 1;//$_REQUEST['only_booked_resources'];  //FixIn: 7.0.1.51
    }
     
    
    /**
	 * Define Request View Params
     * 
     * @param array $param = = array(                                     
                                      'wh_booking_type' => ''            
                                    , 'is_matrix' => false
                                    , 'view_days_num' => '30'
                                        , 'scroll_start_date' => ''
                                        , 'scroll_day' => 0
                                        , 'scroll_month' => 0
                                );    
     */
    public function define_request_view_params_from_params( $param ) {
        //debuge(  $param , $this->options , maybe_unserialize( wp_unslash( $param['options'] ) ) );die;
        if ( isset( $param['wh_booking_type'] ) )    $this->request_args['wh_booking_type'] = $param['wh_booking_type'];          // Used once for comma seperated resources only.            
        
        if (  ( isset( $param['wh_booking_type'] ) ) && ( strpos( $param['wh_booking_type'], ',' ) !== false )  ) 
                                                     $this->request_args['is_matrix'] = true;                    
        if ( isset( $param['view_days_num'] ) )      $this->request_args['view_days_num'] = $param['view_days_num'];        
        if ( isset( $param['scroll_start_date'] ) )  $this->request_args['scroll_start_date'] = $param['scroll_start_date'];                
        if ( isset( $param['scroll_day'] ) )         $this->request_args['scroll_day'] = $param['scroll_day'];        
        if ( isset( $param['scroll_month'] ) )       $this->request_args['scroll_month'] = $param['scroll_month'];        
        if ( isset( $param['wh_trash'] ) )           $this->request_args['wh_trash'] = $param['wh_trash'];
        if ( isset( $param['limit_hours'] ) )        $this->request_args['limit_hours'] = $param['limit_hours'];                                //FixIn: 7.0.1.14
        if ( isset( $param['only_booked_resources'] ) )  $this->request_args['only_booked_resources'] = $param['only_booked_resources'];        //FixIn: 7.0.1.14
        if ( isset( $param['booking_hash'] ) )  	 $this->request_args['booking_hash'] = $param['booking_hash'];        						//FixIn: 8.1.3.5
		if ( ( empty( $this->options ) ) && ( isset( $param['options'] ) )  ) {
			$this->options = maybe_unserialize( wp_unslash( $param['options'] ) );        //FixIn: 7.2.1.14
        }
			
    }
    

    /**
	 * Get  D A T E S  and  T I M E S  from   B o o k i n g s
     * 
     * @param array $bookings - Booking input array
     * @return array          - array( $dates_array, $time_array_new )  
     */    
    public function wpbc_get_dates_and_times_for_timeline( $bookings ) {

        // Generate: Array ( [0] => array(), [3600] =>  array(), [7200] => array(), ..... [43200] => array(),.... [82800] => array()   ) 
        $fixed_time_hours_array = array();                                      
        for ( $tt = 0; $tt < 24; $tt++ ) {
            $fixed_time_hours_array[$tt * 60 * 60] = array();
        }

        // Dates array: { '2012-12-24' => array( Booking ID 1, Booking ID 2, ....), ... }
        $dates_array = $time_array = array();
        foreach ( $bookings as $bk ) {
            foreach ( $bk->dates as $dt ) {

                // Transform from MySQL date to PHP date
                $dt->booking_date = trim( $dt->booking_date );
                $dta = explode( ' ', $dt->booking_date );
                $tms = $dta[1];
//FixIn: 8.2.1.21
//if ( substr( $dta[1], - 1 ) == '2' ) { continue; }
                $tms = explode( ':', $tms );                                        // array('13','30','40')
                $dta = $dta[0];
                $dta = explode( '-', $dta );                                        // array('2012','12','30')
                $php_dt = mktime( $tms[0], $tms[1], $tms[2], $dta[1], $dta[2], $dta[0] );

                if ( ( isset( $dt->type_id ) ) && (!empty( $dt->type_id )) )
                    $date_bk_res_id = $dt->type_id;
                else
                    $date_bk_res_id = $bk->booking_type;


                $my_date = date_i18n( "Y-m-d", $php_dt );                                // '2012-12-01';
                if ( !isset( $dates_array[$my_date] ) ) {
                    $dates_array[$my_date] = array( array( 'id' => $bk->booking_id, 'resource' => $date_bk_res_id ) );
                } else {
                    $dates_array[$my_date][] = array( 'id' => $bk->booking_id, 'resource' => $date_bk_res_id );
                }

                $my_time = date_i18n( "H:i:s", $php_dt );                                // '21:55:01';

                $my_time_index = explode( ':', $my_time );
                $my_time_index = (int) ($my_time_index[0] * 60 * 60 + $my_time_index[1] * 60 + $my_time_index[2]);

                $my_time = strtotime( $my_time );                     //FixIn: 8.1.1.6

                if ( !isset( $time_array[$my_date] ) ) {
                    $time_array[$my_date] = array( $my_time_index => array( $my_time => array( 'id' => $bk->booking_id, 'resource' => $date_bk_res_id ) ) );
                } else {

                    if ( !isset( $time_array[$my_date][$my_time_index] ) )
                        $time_array[$my_date][$my_time_index] = array( $my_time => array( 'id' => $bk->booking_id, 'resource' => $date_bk_res_id ) );
                    else {
                        if ( !isset( $time_array[$my_date][$my_time_index][$my_time] ) )
                            $time_array[$my_date][$my_time_index][$my_time] = array( 'id' => $bk->booking_id, 'resource' => $date_bk_res_id );
                        else {
                            $my_time_inc = 3;
                            while ( isset( $time_array[$my_date][$my_time_index][$my_time + $my_time_inc] ) ) {
                                $my_time_inc++;
                            }
                            //Just in case if we are have the booking in the same time, so we are
                            $time_array[$my_date][$my_time_index][($my_time + $my_time_inc)] = array( 'id' => $bk->booking_id, 'resource' => $date_bk_res_id ); 
                        }
                    }
                }
            }
        }

//debuge($time_array);
        // Sorting ..........
        foreach ( $time_array as $key => $value_t ) {                           // Sort the times from lower to higher
            ksort( $value_t );
            $time_array[$key] = $value_t;
        }
        ksort( $time_array );                                                   // Sort array by dates from lower to higher.
//debuge($time_array);
        /* $time_array:
          $key_date     $value_t
          [2012-12-13] => Array ( $tt_index          $times_bk_id_array
          [44401] => Array ( [12:20:01] => 19)
          ),
          [2012-12-14] => Array (
          [10802] => Array([03:00:02] => 19),
          [43801] => Array([12:10:01] => 2)
          ),
          .... */

        $time_array_new = array();
        foreach ( $time_array as $key_date => $value_t ) {                          // fill the $time_array_new - by bookings of full dates....
            $new_times_array = $fixed_time_hours_array;                             // Array ( [0] => Array, [3600] => Array, [7200] => Array .....

            foreach ( $value_t as $tt_index => $times_bk_id_array ) {               //  [44401] => Array ( [12:20:01] => 19 ), .....
                $tt_index_round = floor( ($tt_index / 60) / 60 ) * 60 * 60;         // 14400, 18000,
                $is_bk_for_full_date = $tt_index % 10;                              // 0, 1, 2

                switch ( $is_bk_for_full_date ) {
                    case 0:                                                         // Full date - fill every time slot
                        foreach ( $new_times_array as $round_time_slot => $bk_id_array ) {
                            $new_times_array[$round_time_slot] = array_merge( $bk_id_array, array_values( $times_bk_id_array ) );
                        }
                        unset( $time_array[$key_date][$tt_index] );
                        break;

                    case 1: break;
                    case 2: break;
                    default: break;
                }
            }
            if ( count( $time_array[$key_date] ) == 0 )
                unset( $time_array[$key_date] );

            $time_array_new[$key_date] = $new_times_array;
        }
//debuge($time_array_new);
//debuge($time_array);        
        foreach ( $time_array as $key_date => $value_t ) {
            $new_times_array_for_day_start = $new_times_array_for_day_end = array();
            foreach ( $value_t as $tt_index => $times_bk_id_array ) {               //  [44401] => Array ( [12:20:01] => 19 ), .....
                $tt_index_round = floor( ($tt_index / 60) / 60 ) * 60 * 60;         // 14400, 18000,
//debuge($tt_index, $tt_index_round);                
                $is_bk_for_full_date = $tt_index % 10;                              // 0, 1, 2

                if ( $is_bk_for_full_date == 1 ) {
                    if ( !isset( $new_times_array_for_day_start[$tt_index_round] ) )
                        $new_times_array_for_day_start[$tt_index_round] = array();
                    $new_times_array_for_day_start[$tt_index_round] = array_merge( $new_times_array_for_day_start[$tt_index_round], array_values( $times_bk_id_array ) );
                }
                if ( $is_bk_for_full_date == 2 ) {

                    // Its mean that  the booking is finished exactly  at  the beginig of this hour, 
                    // so  we will not fill the end of booking in this hour, but in previous
                    if ( ($tt_index_round - $tt_index) == -2 ) {
                        $tt_index_round = $tt_index_round - 60 * 60;
                    }

                    if ( !isset( $new_times_array_for_day_end[$tt_index_round] ) )
                        $new_times_array_for_day_end[$tt_index_round] = array();
                    $new_times_array_for_day_end[$tt_index_round] = array_merge( $new_times_array_for_day_end[$tt_index_round], array_values( $times_bk_id_array ) );
                }
            }
            $time_array[$key_date] = array( 'start' => $new_times_array_for_day_start, 'end' => $new_times_array_for_day_end );
        }
//debuge($time_array);        
        /* $time_array
          [2012-12-24] => Array
          (
          [start] => Array (
          [68400] => Array ( [0] => 15 ) )
          [end] => Array (
          [64800] => Array ( [0] => 6 ) )

          ) */
        $fill_this_date = array();
//debuge($time_array_new['2017-01-13']);
        

        // Fil specific times based on start  and end times
        foreach ( $time_array_new as $ddate => $ttime_round_array ) {
            foreach ( $ttime_round_array as $ttime_round => $bk_id_array ) {    // [3600] => Array( [0] => Array ( [id] => 214 [resource] => 9 ), [1] => Array ( [id] => 154 [resource] => 7    

                if ( isset( $time_array[$ddate] ) ) {

                    if ( isset( $time_array[$ddate]['start'][$ttime_round] ) )  // array
                        $fill_this_date = array_merge( $fill_this_date, array_values( $time_array[$ddate]['start'][$ttime_round] ) );
//debuge($fill_this_date);
                    $time_array_new[$ddate][$ttime_round] = array_merge( $time_array_new[$ddate][$ttime_round], $fill_this_date );

//debuge($ddate, $ttime_round, $time_array_new[$ddate][$ttime_round]);

                    //FixIn: 7.0.1.16 - advanced checking about delettion  of times in $time_array[$ddate]['end']
                    
                    // End array checking for deleting.
                    if ( isset( $time_array[$ddate]['end'][$ttime_round] ) )    // array
                        foreach ( $time_array[$ddate]['end'][$ttime_round] as $toDelete ) {
//if ( $ddate == '2017-01-13' ) {
//    debuge('$toDelete, $fill_this_date',$toDelete, $fill_this_date);
//}
                            $fill_this_date_keys_to_delete = array();
                            foreach ( $fill_this_date as $fill_this_date_key => $check_element_array ) {        // [0] => Array ( [id] => 54 [resource] => 5 )
                                  
                                if (                                            // Check  if arrays equals - identical
                                           ( is_array( $toDelete ) && is_array( $check_element_array ) )
                                        && ( count( $toDelete ) == count( $check_element_array ) )
                                        && ( array_diff( $toDelete, $check_element_array ) === array_diff( $check_element_array, $toDelete ) )
                                    )  {       
                                      $fill_this_date_keys_to_delete[] = $fill_this_date_key;               // $toDelete element exist so  save key in original  array 
                                }
                            }

                            $fill_this_date_new = array();
                            foreach ( $fill_this_date as $fill_this_date_key => $fill_this_date_value ) {
                                if (  ! in_array( $fill_this_date_key, $fill_this_date_keys_to_delete ) ) {
                                    $fill_this_date_new[] = $fill_this_date_value;
                                }
                            }
                            $fill_this_date = $fill_this_date_new;              // Reassign cleared array (with  deleted values)
                            
                            
                            if ( !empty( $fill_this_date ) ) {
//                                $fill_this_date = array_diff( $fill_this_date, array( $toDelete ) );
                                
//if ( $ddate == '2017-01-13' ) {
//    debuge('AFTER:: $toDelete, $fill_this_date',$toDelete, $fill_this_date);
//}                                
                            }
                        }
                }
            }
        }

        return array( $dates_array, $time_array_new );
    }


    ////////////////////////////////////////////////////////////////////////////
    //  C a l e n d a r    T i m e l i n e       ///////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    
    private function wpbc_dates_only_of_specific_resource( $booked_dates_array, $resource_id, $bookings ) {

        foreach ( $booked_dates_array as $key => $value ) {

            $new_array = array();
            foreach ( $value as $bk_id ) {
                if ( $bk_id['resource'] == $resource_id ) {
                    $new_array[] = $bk_id['id'];
                }
            }
            if ( !empty( $new_array ) )
                $booked_dates_array[$key] = $new_array;
            else
                unset( $booked_dates_array[$key] );
        }
        return $booked_dates_array;
    }

    
    private function wpbc_times_only_of_specific_resource( $time_array_new, $resource_id, $bookings ) {

        foreach ( $time_array_new as $date_key => $times_array ) {

            foreach ( $times_array as $time_key => $value ) {

                $new_array = array();
                foreach ( $value as $bk_id ) {

                    if ( $bk_id['resource'] == $resource_id ) {
                        $new_array[] = $bk_id['id'];
                    }
                }
                $time_array_new[$date_key][$time_key] = $new_array;
            }
        }
        return $time_array_new;
    }


    private function wpbc_write_bk_id_css_classes( $prefix, $previous_booking_id ) {

        if ( (!isset( $previous_booking_id )) || (empty( $previous_booking_id )) )
            return '';

        if ( is_string( $previous_booking_id ) )
            $bk_id_array = explode( ',', $previous_booking_id );
        else if ( is_array( $previous_booking_id ) )
            $bk_id_array = $previous_booking_id;
        else // Some Unknown situation
            return '';

        $bk_id_array = array_unique( $bk_id_array );

        // If we are have several bookings,  so  add this special class
        if ( count( $bk_id_array ) > 1 )
            $css_class = 'here_several_bk_id ';
        else
            $css_class = '';

        foreach ( $bk_id_array as $bk_id ) {
            $css_class .= $prefix . $bk_id . ' ';
        }

        return $css_class;
    }    

    
    ////////////////////////////////////////////////////////////////////////////
    //  S h o w
    ////////////////////////////////////////////////////////////////////////////
    
    /** Header */
    public function wpbc_show_timeline_header_row( $start_date = false ) {
        
        $current_resource_id = '';
        
        $is_matrix = $this->request_args['is_matrix'];
                
        $view_days_num = $this->request_args['view_days_num'];

        
        $start_hour_for_1day_view = 0;                                          //FixIn: 7.0.1.14
        $end_hour_for_1day_view   = 24;
        $limit_hours = 24;
        
        if ( $is_matrix ) {
            
            // MATRIX VIEW
            switch ( $view_days_num ) {
                case '1':
                    $days_num = 1;
                    $cell_width = '99%';
                    $dwa = $this->week_days_titles['full'];
                    $time_selles_num = 24;
                    if ( isset( $this->request_args[ 'limit_hours' ] ) ) {     //FixIn: 7.0.1.14
                        $limit_hours = explode(',',$this->request_args[ 'limit_hours' ]);
                        $start_hour_for_1day_view = intval( $limit_hours[0] );
                        $end_hour_for_1day_view   = intval( $limit_hours[1] );
                        $limit_hours = $limit_hours[1] - $limit_hours[0];
                    }
                    break;
                case '7':
                    $days_num = 7;
                    $cell_width = '13.8%';
                    $dwa = $this->week_days_titles['3'];
                    $time_selles_num = 1;
                    break;
                case '30':
                    $days_num = 31;
                    $days_num = intval( date_i18n('t',$start_date) );           // num of days in the specific  month,  wchih  relative to $real_date from  header        //FixIn: 7.0.1.47                    
                    $cell_width = '3%';
                    $dwa = $this->week_days_titles['3'];
                    $time_selles_num = 1;
                    break;
                case '60':
                    $days_num = 62;
                    $cell_width = '1.5%';
                    $dwa = $this->week_days_titles['1'];
                    $time_selles_num = 1;
                    break;
                default:  // 30
                    $days_num = 31;
                    $cell_width = '3%';
                    $dwa = $this->week_days_titles['3'];
                    $time_selles_num = 1;
                    break;
            }
            
        } else {

            switch ( $view_days_num ) {
                case '90':
                    $days_num = 7;
                    $cell_width = '13.8%';
                    $dwa = $this->week_days_titles['full'];
                    $time_selles_num = 1;
                    break;
                case '365':
                    $days_num = 32;
                    $days_num = intval( date_i18n('t',$start_date) );           // num of days in the specific  month,  wchih  relative to $real_date from  header        //FixIn: 7.0.1.47                    
                    $cell_width = '3%';
                    $dwa = $this->week_days_titles['3'];
                    $time_selles_num = 1;
                    break;
                default:  // 30
                    $days_num = 1;
                    $cell_width = '99%';
                    $dwa = $this->week_days_titles['3'];
                    $time_selles_num = 24;
                    if ( isset( $this->request_args[ 'limit_hours' ] ) ) {     //FixIn: 7.0.1.14
                        $limit_hours = explode(',',$this->request_args[ 'limit_hours' ]);
                        $start_hour_for_1day_view = intval( $limit_hours[0] );
                        $end_hour_for_1day_view   = intval( $limit_hours[1] );
                        $limit_hours = $limit_hours[1] - $limit_hours[0];    
                    }                    
                    break;
            }
        }

        if ( $start_date === false ) {
            
            if ( ! empty( $this->request_args['scroll_start_date'] ) )           
                list( $start_year, $start_month, $start_day ) = explode( '-', $this->request_args['scroll_start_date'] );   // scroll_start_date=2013-07-01
            else 
                list( $start_year, $start_month, $start_day ) = explode( '-', date_i18n( 'Y-n-j' ) );
            
        } else {
                list( $start_year, $start_month, $start_day ) = explode( '-', date_i18n( 'Y-m-d', $start_date ) );
        }
        
        ?>
        <div class="container-fluid <?php if ($is_matrix) { echo ' wpbc_tl_matrix_resources '; } else { echo ' wpbc_tl_single_resource '; } ?> "><div class="row"><div class="col-md-12">
            <div id="timeline_scroller<?php echo $current_resource_id; ?>" class="wpbc_tl_scroller">
                <div class="wpbc_tl_frame_dates" ><?php
                
                    $previous_month = '';
                    
                    $bk_admin_url_today = wpbc_get_params_in_url( wpbc_get_bookings_url( false, false ), array( 'scroll_month', 'scroll_day', 'scroll_start_date' ) );
                    
                    for ( $d_inc = 0; $d_inc < $days_num; $d_inc++ ) {

                        $real_date = mktime( 0, 0, 0, $start_month, ($start_day + $d_inc ), $start_year );

                        if ( date_i18n( 'm.d.Y' ) == date_i18n( "m.d.Y", $real_date ) )
                            $is_today = ' today_date ';
                        else
                            $is_today = '';

                        $yy = date_i18n( "Y", $real_date );    //2012
                        $mm = date_i18n( "m", $real_date );    //09
                        $dd = date_i18n( "d", $real_date );    //31
                        $ww = date_i18n( "N", $real_date );    //7
                        $day_week = $dwa[$ww];          //Su

                        $day_title = $dd . ' ' . $day_week;
                        if ( $is_matrix ) {
                            if ( $view_days_num == 1 ) {
                                $day_title = wpbc_get_date_in_correct_format( $yy . '-' . $mm . '-' . $dd . ' 00:00:00' );
                                //$day_title  = $day_week . '<br/>' .  $day_title[0];
                                $day_title = '(' . $day_week . ') &nbsp; ' . $day_title[0];                           //FixIn:6.0.1                          
                            }
                            if ( $view_days_num == 7 ) {
                                $day_title = wpbc_get_date_in_correct_format( $yy . '-' . $mm . '-' . $dd . ' 00:00:00' );
                                $day_title = $day_week . '<br/>' . $day_title[0];
                            }
                            if ( $view_days_num == 30 ) {
                                $day_title = $dd . '<br/>' . $day_week;
                            }

                            if ( $view_days_num == 60 ) {
                                $day_title = $dd . '<br/>' . $day_week;
                            }
                        } else {
                            if ( $view_days_num == 1 ) {
                                $day_title = wpbc_get_date_in_correct_format( $yy . '-' . $mm . '-' . $dd . ' 00:00:00' );
                                $day_title = $day_week . '<br/>' . $day_title[0];
                            }
                            if ( $view_days_num == 7 ) {
                                $day_title = wpbc_get_date_in_correct_format( $yy . '-' . $mm . '-' . $dd . ' 00:00:00' );
                                $day_title = $day_week . '<br/>' . $dd;
                            }
                            if ( $view_days_num == 30 ) {
                                $day_title = __( 'Times', 'booking' );
                            }
                            if ( $view_days_num == 90 ) {
                                $day_title = $day_week;
                            }
                            if ( $view_days_num == 365 ) {
                                $day_title = $dd;
                            }
                        }
                        $day_filter_id = $yy . '-' . $mm . '-' . $dd;

                        if ( $previous_month != $mm ) {
                            $previous_month = $mm;
                            $month_title = date_i18n( "F", $real_date );    //09
                            $month_class = ' new_month ';
                        } else {
                            $month_title = '';
                            $month_class = '';
                        }
                     
                        ?>
                        <div id="cell_<?php  echo $current_resource_id . '_' . $day_filter_id ; ?>" 
                             class="wpbc_tl_day_cell wpbc_tl_day_cell_header wpbc_time_in_days_num_<?php echo $view_days_num;
                                 ?> wpbc_tl_weekday<?php echo $ww . ' ' . $day_filter_id . ' ' . $month_class; ?>" 
                             style="<?php echo 'width:' . $cell_width . ';'; ?>" ><?php 

                                if ($month_title != '') { 
                                    ?><div class="month_year"><?php echo $month_title .', ' . $yy ;?></div><?php 
                                }
                                if ( ( $view_days_num==30 ) || ( $view_days_num == 60) ) {

                                	if ( ! $this->is_frontend ) {
		                                ?><a href='<?php echo $bk_admin_url_today . '&scroll_start_date=' . $yy . '-' . $mm . '-' . $dd; ?>'><?php
	                                }
											?> <div class="day_num day_num<?php echo $d_inc ?>"><?php echo $day_title;?></div><?php
									if ( ! $this->is_frontend ) {
										?></a><?php
									}
                                    
                                } else {
                                    ?><div class="day_num day_num<?php echo $d_inc ?>"><?php echo $day_title;?></div><?php
                                }

                                // T i m e   c e l l s
                                $tm = floor( 24 / $time_selles_num );
                                for ( $tt = 0; $tt < $time_selles_num; $tt++ ) { ?>
                                    
                                    <?php  if ( ( $tt < $start_hour_for_1day_view ) || ( $tt > $end_hour_for_1day_view ) ) { continue; } //FixIn: 7.0.1.14 ?>
                                    
                                    <div class="wpbc_time_section_in_day wpbc_time_section_in_day_header time_hour<?php echo ($tt*$tm); 
                                             ?> wpbc_time_in_days_num_<?php echo $view_days_num;?>" style="<?php 
                                             
                                    if ( $limit_hours < 24 ) {     //FixIn: 7.0.1.14
                                       $style_width = 'width:' . floatval( floor( round( 100 / $limit_hours , 2 ) * 10 ) / 10 - 0.2 ) . '%;';
                                       echo $style_width;
                                    }
                                    ?>" ><?php

	                                //FixIn: 8.1.3.34
									$bc_time_format = get_bk_option( 'booking_time_format');
									if( ! empty( $bc_time_format ) ){                            //FixIn: 8.2.1.2
										$time_show = date_i18n( str_replace( ':i', '', get_bk_option( 'booking_time_format' ) ), mktime( $tt * $tm , 0, 0 ) );
										echo ( $view_days_num < 31 ) ? $time_show : '';
									} else {
										echo ( ( $view_days_num < 31 ) ? ( ( ($tt*$tm) < 10?'0':'') . ($tt*$tm) . '<sup>:00</sup>' ) : '' );
									}
                                    ?></div><?php
                                }
                                
                      ?></div><?php        

                    } 
                    ?>
                </div>
            </div>
        </div></div></div><?php

        return $real_date ;
    }
    
    
    /** Row */
    public function wpbc_show_timeline_booking_row( $current_resource_id, $start_date, $booking_data = array() ) {

        $is_matrix = $this->request_args['is_matrix'];

        $start_hour_for_1day_view = 0;                                          //FixIn: 7.0.1.14
        $end_hour_for_1day_view   = 24;
        $limit_hours = 24;
        
        $booked_dates_array = $booking_data[0];
        $bookings           = $booking_data[1];
        $booking_types      = $booking_data[2];
        $time_array_new     = $booking_data[3];

        // Remove dates and Times from  the arrays, which is not belong to the $current_resource_id
        // We do not remove it only, when  the $current_resource_id - is empty - OLD ALL Resources VIEW
        if ( empty( $current_resource_id ) ) {
            $current_resource_id = 1;
        }

        $booked_dates_array = $this->wpbc_dates_only_of_specific_resource( $booked_dates_array, $current_resource_id, $bookings );
        $time_array_new     = $this->wpbc_times_only_of_specific_resource( $time_array_new, $current_resource_id, $bookings );
//debuge($time_array_new);
        $current_date = $start_date;

        $bk_url_listing = wpbc_get_bookings_url( true, false );

        // Initial  params
        $view_days_num = $this->request_args['view_days_num'];

//$max_rows_number =  
//debuge( date_i18n('Y-m-d',$start_date)) ;        
        if ( ! $is_matrix ) {                                             // Single booking resource

            switch ($view_days_num) {
                case '90':
                    $days_num = 7;
                    $cell_width = '13.8%';
                    $dwa = $this->week_days_titles['full'];
                    $time_selles_num  = 1;
                    break;
                case '365':
                    $days_num = 32;
                    $days_num = intval( date_i18n('t',$start_date) );           // num of days in the specific  month,  wchih  relative to $real_date from  header        //FixIn: 7.0.1.47
                    $cell_width = '3%';
                    $dwa = $this->week_days_titles['1'];
                    $time_selles_num  = 1;
                    break;
                default:  // 30
                    $days_num = 1;
                    $cell_width =  '99%';;
                    $dwa = $this->week_days_titles['3'];
                    $time_selles_num  = 24;//25;

                    if ( isset( $this->request_args[ 'limit_hours' ] ) ) {     //FixIn: 7.0.1.14
                        $limit_hours = explode(',',$this->request_args[ 'limit_hours' ]);
                        $start_hour_for_1day_view = intval( $limit_hours[0] );
                        $end_hour_for_1day_view   = intval( $limit_hours[1] );
                        $limit_hours = $limit_hours[1] - $limit_hours[0];    
                    }                    
                    
                    //$view_days_num = 1;
                    break;
            }

        } else {                                                                // Multiple booking resources
            //$view_days_num = 365;
            switch ($view_days_num) {
                case '1':
                    $days_num = 1;
                    $cell_width = '99%';//(7*4.75*31) . 'px';
                    $dwa = $this->week_days_titles['full'];
                    $time_selles_num  = 24;
                    
                    if ( isset( $this->request_args[ 'limit_hours' ] ) ) {     //FixIn: 7.0.1.14
                        $limit_hours = explode(',',$this->request_args[ 'limit_hours' ]);
                        $start_hour_for_1day_view = intval( $limit_hours[0] );
                        $end_hour_for_1day_view   = intval( $limit_hours[1] );
                        $limit_hours = $limit_hours[1] - $limit_hours[0];    
                    }                    
                    
                    break;
                case '7':
                    $days_num = 7;
                    $cell_width = '13.8%';//(4.75*31) . 'px';
                    $dwa = $this->week_days_titles['full'];
                    $time_selles_num  = 4;
                    break;
                case '60':
                    $days_num = 62;
                    $cell_width = '1.5%';//(12) . 'px';;
                    $dwa = $this->week_days_titles['1'];
                    $time_selles_num  = 1;
                    break;
                case 'old_365':
                    $days_num = 365;
                    $cell_width = '1%';//(2) . 'px';;
                    $time_selles_num  = 1;
                    $dwa = $this->week_days_titles['1'];
                    break;

                default:  // 30
                    $days_num = 32;
                    $days_num = intval( date_i18n('t',$start_date) );           // num of days in the specific  month,  wchih  relative to $real_date from  header        //FixIn: 7.0.1.47                    
                    $cell_width = '3%';//(31) . 'px';;
                    $dwa = $this->week_days_titles['3'];
                    $time_selles_num  = 1;//25;
                    break;
            }
        }

        if ( $start_date === false ) {
            
            if ( ! empty( $this->request_args['scroll_start_date'] ) )           
                list( $start_year, $start_month, $start_day ) = explode( '-', $this->request_args['scroll_start_date'] );   // scroll_start_date=2013-07-01
            else 
                list( $start_year, $start_month, $start_day ) = explode( '-', date_i18n( 'Y-n-j' ) );
            
        } else {
                list( $start_year, $start_month, $start_day ) = explode( '-', date_i18n( 'Y-m-d', $start_date ) );
        }
        
        $previous_booking_id = false;
        
        ?>
        <div class="container-fluid <?php if ($is_matrix) { echo ' wpbc_tl_matrix_resources '; } else { echo ' wpbc_tl_single_resource '; } ?>"><div class="row"><div class="col-md-12">

        <div id="timeline_scroller<?php echo $current_resource_id; ?>" class="wpbc_tl_scroller">

        <div class="wpbc_tl_frame_dates"  >
        <?php
            
            $is_approved = false;
            $previous_month = '';
            for ( $d_inc = 0; $d_inc < $days_num; $d_inc++ ) {

                $real_date = mktime( 0, 0, 0, $start_month, ($start_day + $d_inc ), $start_year );

                if ( date_i18n( 'm.d.Y' ) == date_i18n( "m.d.Y", $real_date ) )
                    $is_today = ' today_date ';
                else {
                    if ( date_i18n( 'Y.m.d' ) > date_i18n( "Y.m.d", $real_date ) )
                        $is_today = ' past_date ';
                    else
                        $is_today = '';
                }

                $yy = date_i18n( "Y", $real_date );    //2012
                $mm = date_i18n( "m", $real_date );    //09
                $dd = date_i18n( "d", $real_date );    //31
                $ww = date_i18n( "N", $real_date );    //7

                $day_week = $dwa[$ww];          //Su

                $day_title = $dd;
                if ( $view_days_num == 1 ) {
                    $day_title = wpbc_get_date_in_correct_format( $yy . '-' . $mm . '-' . $dd . ' 00:00:00' );
                    $day_title = $day_week . ', ' . $day_title[0];
                }
                if ( $view_days_num == 7 ) {
                    $day_title = wpbc_get_date_in_correct_format( $yy . '-' . $mm . '-' . $dd . ' 00:00:00' );
                    $day_title = $day_week . ', ' . $dd;
                }
                if ( $view_days_num == 30 ) {
                    $day_title = __( 'Times', 'booking' );
                }

                $day_filter_id = $yy . '-' . $mm . '-' . $dd;

                if ( $previous_month != $mm ) {
                    $previous_month = $mm;
                    $month_title = date_i18n( "F", $real_date );    //09
                    $month_class = ' new_month ';
                } else {
                    $month_title = '';
                    $month_class = '';
                }
                

                ?><div  id="cell_<?php  echo $current_resource_id . '_' . $day_filter_id ; ?>"
                      class="wpbc_tl_day_cell wpbc_tl_weekday<?php echo $ww . ' ';  echo $is_today; echo  ' '.$day_filter_id.' '.$month_class ; ?>"
                      style="<?php echo 'width:' . $cell_width . ';'; ?>"                          
                      ><?php
                      
                        // Show date in timeline cell.
                    
                        // if ( in_array( $ww, array( 1, 3, 6 ) ) )             // Show number of days only for the specific week days - for speeder loading.
                        echo '<i class="wpbc_day_cell_number wpbc_time_in_days_num_' . $view_days_num . ' " >' 
                                . $dd . ( ($view_days_num == '90') ? '/' . $mm : '') 
							 // . ( ($view_days_num == '90') ?  $mm . '/' : '') . $dd									// Show: "month/day" instead of "day/month"
                           . '</i>';
                        

                        $title_in_day = $title = $title_hint ='';
                        $is_bk = 0;

                        if ( $time_selles_num != 24 ) {                         //  Full     D a t e s
                            if ( $booked_dates_array !== false ) {

                                $link_id_parameter = array();
                                if ( isset( $booked_dates_array[$day_filter_id] ) ) {    // This date is    B O O K E D
                                    $is_bk = 1;
                                    $booked_dates_array[$day_filter_id] = array_unique( $booked_dates_array[$day_filter_id] );
                                    foreach ( $booked_dates_array[$day_filter_id] as $bk_id ) {

                                        $booking_num_in_day = count( $booked_dates_array[$day_filter_id] );
                                        
                                        if ( ($previous_booking_id != $bk_id) || ($booking_num_in_day > 1) ) {
                                            
                                            // if the booking take for the several  days, so then do not show title in the other days
                                            $my_bk_info = $this->wpbc_get_booking_info_4_tooltip( $bk_id, $bookings, $booking_types, $title_in_day, $title, $title_hint );

                                            $title_in_day   = htmlspecialchars_decode( $my_bk_info[0] );                //FixIn: 8.4.2.8
                                            $title          = $my_bk_info[1];
                                            $title_hint     = $my_bk_info[2];
                                            $is_approved    = $my_bk_info[3];
                                        }
                                        
                                        if ( $booking_num_in_day > 1 ) {
                                            $previous_booking_id .= ',' . $bk_id;
                                        } else
                                            $previous_booking_id = $bk_id;

                                        $link_id_parameter[] = $bk_id;
                                    }
                                } else
                                    $previous_booking_id = false;


                                // Just one day cell

                                $title_hint = str_replace( '"', "", $title_hint );
                                $link_id_parameter = implode( ',', $link_id_parameter );
                                if ( strpos( $title_in_day, ',' ) !== false ) {
                                    $title_in_day = explode( ',', $title_in_day );
                                    $title_in_day = $title_in_day[0] . ' ... ' . $title_in_day[(count( $title_in_day ) - 1)];
                                    $title_in_day = '<span style=\'font-size:7px;\'>' . $title_in_day . '</span>';
                                }

                                // Show the circle with  bk ID(s) in a day
                                if ( ! empty($title_in_day ) ) {

                                    $is_show_popover_in_timeline  = wpbc_is_show_popover_in_timeline( $this->is_frontend, $this->request_args['booking_hash'] );    	//FixIn: 8.1.3.5

                                    if ( $is_show_popover_in_timeline )
                                        echo '<a                                        
                                                    href="javascript:void(0)" 
                                                    data-content="' . $title_hint . '"
                                                    data-original-title="' . $title . '"
                                                    class="' . $this->wpbc_write_bk_id_css_classes( 'a_bk_id_', $previous_booking_id ) 
                                                             . ' popover_bottom popover_click  ' . ( ($title != '') ? 'first_day_in_bookin' : '' ) 
                                                . '" >' 
                                                . $title_in_day . '</a>';
                                    else
                                        echo '<a href="javascript:void(0)" 
                                                 class="' . $this->wpbc_write_bk_id_css_classes( 'a_bk_id_', $previous_booking_id ) 
                                                          . ( ($title != '') ? 'first_day_in_bookin' : '' ) 
                                                . '" >' . $title_in_day . '</a>';
                                }
                                $tm = floor( 24 / $time_selles_num );
                                $tt = 0;
                                $my_bkid_title = '';
                                
                                if (        ( ! empty( $previous_booking_id ) ) 
                                    && isset( $bookings[$previous_booking_id] )            
                                    && isset( $bookings[$previous_booking_id]->trash ) )                        //FixIn:6.1.1.10    
                                    $is_trash = $bookings[$previous_booking_id]->trash;
                                else 
                                    $is_trash = false;
                                
								$is_blank_bookings = false;														//FixIn: 7.2.1.8
                                if ( ! empty( $previous_booking_id ) ) {                                        //FixIn: 7.0.1.40
                                    if (    ( isset($bookings[$previous_booking_id]->form_data['email']) ) 
                                         && ( $bookings[$previous_booking_id]->form_data['email'] == 'admin@blank.com' )
                                        )  $is_blank_bookings = true;
                                }

                                $css_class_additional = apply_filters( 'wpbc_timeline_booking_header_css', '', $previous_booking_id, $bookings );           //FixIn: 7.0.1.41
                                
                                echo '<div class="' . $this->wpbc_write_bk_id_css_classes( 'cell_bk_id_', $previous_booking_id ) 
                                                    . ' wpbc_time_section_in_day timeslots_in_this_day' . $time_selles_num 
                                                    . ' time_hour' . ($tt * $tm) 
                                                    . '  wpbc_time_in_days_num_' . $view_days_num 
                                                    . ' ' . ( $is_bk ? 'time_booked_in_day' : '' ) 
                                                    . ' ' . ( $is_trash ? ' booking_trash ': '')						//FixIn:6.1.1.10    
                                                    . ' ' . ( $is_blank_bookings ? ' booking_blank ': '')               //FixIn: 7.2.1.8
                                                    . ' ' . ( $is_approved ? 'approved' : '' ) 
                                                    . ' ' . $css_class_additional
                                                    . '">' 
                                        . ( $is_bk ? $my_bkid_title : '' ) 
                                    . '</div>';
                            }
                        }


                        if ($time_selles_num == 24 ) {                          // Time Slots in a date
                            
                            $is_showed_title_in_row = 0;                        //FixIn: 7.0.1.16
                            
                            if ( isset( $time_array_new[$day_filter_id] ) ) {
//debuge($time_array_new[$day_filter_id]);
                                // Loop time cells  /////////////////////////////////////////////////////////////////////////////////////////////////
                                $tm = floor( 24 / $time_selles_num );
                                for ( $tt = 0; $tt < $time_selles_num; $tt++ ) {

                                    
                                    
                                    $my_bk_id_array = $time_array_new[$day_filter_id][$tt * 60 * 60];
                                    $my_bk_id_array = array_unique( $my_bk_id_array ); //remove dublicates

                                    if ( empty( $my_bk_id_array ) ) {       // Time cell  is    E m p t y

                                        $is_bk = 0;
                                        $previous_booking_id = false;
                                        $my_bkid_title = $title_in_day = $title = $title_hint = '';
                                    } else {                                // Time cell is     B O O K E D
                                        $is_bk = 1;
                                        $link_id_parameter = array();

                                        if ( ($previous_booking_id !== $my_bk_id_array) || ($previous_booking_id === false) ) {
                                            $my_bkid_title = $title_in_day = $title = $title_hint = '';
                                            foreach ( $my_bk_id_array as $bk_id ) {

                                                $my_bk_info = $this->wpbc_get_booking_info_4_tooltip( $bk_id, $bookings, $booking_types, $title_in_day, $title, $title_hint );

                                                $title_in_day        = htmlspecialchars_decode( $my_bk_info[0] );       //FixIn: 8.4.2.8
                                                $title               = $my_bk_info[1];
                                                $title_hint          = $my_bk_info[2];
                                                $is_approved         = $my_bk_info[3];
                                                $link_id_parameter[] = $bk_id;
                                            }
                                        } else {
                                            if ( $is_showed_title_in_row == 1 ){                        //FixIn: 7.0.1.16
                                                $my_bkid_title = $title_in_day = $title = $title_hint = '';
                                            }
                                        }
                                        $previous_booking_id = $my_bk_id_array;


                                        $title_hint = str_replace( '"', "", $title_hint );
                                        $link_id_parameter = implode( ',', $link_id_parameter );
                                        if ( strpos( $title_in_day, ',' ) !== false ) {
                                            $title_in_day = explode( ',', $title_in_day );
                                            $title_in_day = $title_in_day[0] . ' ... ' . $title_in_day[(count( $title_in_day ) - 1)];
                                            $title_in_day = '<span style=\'font-size:7px;\'>' . $title_in_day . '</span>';
                                        }

                                        // Show the circle with  bk ID(s) in a day
                                        if ( ! empty($title_in_day ) ) {
                                            
											$is_show_popover_in_timeline  = wpbc_is_show_popover_in_timeline( $this->is_frontend, $this->request_args['booking_hash'] );    	//FixIn: 8.1.3.5

                                            if ( $is_show_popover_in_timeline )
                                                $my_bkid_title = '<a  
                                                                    href="javascript:void(0)" 
                                                                    data-content="' . $title_hint . '"
                                                                    data-original-title="' . $title . '"
                                                                    class="' . $this->wpbc_write_bk_id_css_classes( 'cell_bk_id_', $previous_booking_id ) 
                                                                             . ' popover_bottom popover_click ' 
                                                                             . ( ($title != '') ? 'first_day_in_bookin' : '' ) 
                                                                        . ' ">' 
                                                                    . $title_in_day 
                                                                . '</a>';
                                            else
                                                $my_bkid_title = '<span  

                                                                    class="' . $this->wpbc_write_bk_id_css_classes( 'cell_bk_id_', $previous_booking_id ) 
                                                                             . ( ($title != '') ? 'first_day_in_bookin' : '' ) 
                                                                        . ' ">' 
                                                                    . $title_in_day 
                                                                . '</span>';
                                        }
                                
                                    }

                                    if ( ( $is_today == ' today_date ' ) && ( intval( date_i18n( 'H' ) ) > ($tt * $tm) ) ) 
                                        $is_past_time = ' past_time ';
                                    else 
                                        $is_past_time = '';

                                    if ( $is_bk )                                   //FixIn:6.1.1.10    
                                        $is_trash = $bookings[ $bk_id ]->trash;
                                    else 
                                        $is_trash = false;
                                    

                                    if ( ( $tt < $start_hour_for_1day_view ) || ( $tt > $end_hour_for_1day_view ) ) { continue; } //FixIn: 7.0.1.14
                                    if ( $limit_hours < 24 ) {     //FixIn: 7.0.1.14
                                       $style_width = 'width:' . floatval( floor( round( 100 / $limit_hours , 2 ) * 10 ) / 10 - 0.2 ) . '%;';
                                    } else {
                                        $style_width = '';
                                    }
                                    
                                    echo '<div class="' 
                                                    . $this->wpbc_write_bk_id_css_classes( 'cell_bk_id_', $previous_booking_id ) 
                                                    . ' wpbc_time_section_in_day timeslots_in_this_day' . $time_selles_num 
                                                    . ' time_hour' . ($tt * $tm) 
                                                    . '  wpbc_time_in_days_num_' . $view_days_num 
                                                    . ' ' . ( $is_bk ? ' time_booked_in_day' . $is_past_time : '' ) 
                                                    . ' ' . ( $is_approved ? 'approved' : '' ) 
                                                    . ' ' . ( $is_trash? ' booking_trash ': '')                  //FixIn:6.1.1.10    
                                                    . '"'
                                            . ' style="' . $style_width . '"'
                                        . '>' 
                                            . ( $is_bk ? $my_bkid_title : '' ) 
                                        . '</div>';
                                    $is_showed_title_in_row = 1;                //FixIn: 7.0.1.16
                                } //////////////////////////////////////////////////////////////////////////////////////////////////////////////

                            } else { // Just  time borders

                                $tm = floor( 24 / $time_selles_num );
                                
                                for ( $tt = 0; $tt < $time_selles_num; $tt++ ) {
                                    
                                    if ( ( $tt < $start_hour_for_1day_view ) || ( $tt > $end_hour_for_1day_view ) ) { continue; } //FixIn: 7.0.1.14
                                    if ( $limit_hours < 24 ) {     //FixIn: 7.0.1.14
                                       $style_width = 'width:' . floatval( floor( round( 100 / $limit_hours , 2 ) * 10 ) / 10 - 0.2 ) . '%;';
                                    } else {
                                        $style_width = '';
                                    }
                                    
                                    echo '<div class="wpbc_time_section_in_day'
                                                  . ' timeslots_in_this_day' . $time_selles_num 
                                                  . ' time_hour' . ($tt * $tm) 
                                                  . '  wpbc_time_in_days_num_' . $view_days_num 
                                                  . ' ' . ( $is_bk ? 'time_booked_in_day' : '' ) 
                                                  . ' ' . ( $is_approved ? 'approved' : '' ) 
                                                    . '"'
                                            . ' style="' . $style_width . '"'
                                        . '>' 
                                            . ( $is_bk ? $my_bkid_title : '' ) 
                                        . '</div>';
                                    $is_showed_title_in_row = 1;                //FixIn: 7.0.1.16
                                }
                            }
                        }


                ?></div><?php
            } 
            
        ?>
        </div>

        </div>

        </div></div></div>
        <?php

        return $current_date ;
    }

    
    /**
	 * Show timeline
     *  All  parameters must  be defined.
     */
    public function show_timeline() {
        $this->wpbc_show_timeline( $this->dates_array, $this->bookings, $this->booking_types, $this->time_array_new );
    }

    
    /** Show Structure of the TimeLine */
    public function wpbc_show_timeline( $dates_array, $bookings, $booking_types, $time_array_new = array() ){

        // Skip showing rows of booking resource(s) in TimeLine or Calendar Overview, if no any exist booking(s) for current view
        $booked_booking_resources = array();                                    //FixIn: 7.0.1.51  
        if ( ! empty( $this->request_args['only_booked_resources'] ) ) {
           
            foreach ( $bookings as $single_booking ) {

                if ( ! empty( $single_booking->booking_type ) )
                    $booked_booking_resources[] = $single_booking->booking_type;

                foreach ( $single_booking->dates as $booking_date_obj ) {
                    if ( ( isset( $booking_date_obj->type_id ) ) && ( ! empty( $booking_date_obj->type_id ) ) )
                        $booked_booking_resources[] = $booking_date_obj->type_id;
                }    
            }
            $booked_booking_resources = array_unique( $booked_booking_resources );
        }

        $view_days_num  = $this->request_args['view_days_num'];                 // Get start date and number of rows, which is depend from the view days mode        
        $is_matrix      = $this->request_args['is_matrix'];
        $scroll_day     = 0;
        $scroll_month   = 0;
        $start_year     = date_i18n( "Y" );
        $start_month    = date_i18n( "m" );                                          // 09            
                        
        if ( ! empty( $this->request_args['scroll_start_date'] ) ) {            // scroll_start_date=2013-07-01
                                                                                // Set the correct  start  date, if was selected the stard date different from the today  in the Filters Tab.
            list( $start_year, $start_month, $start_day ) = explode( '-', $this->request_args['scroll_start_date'] );            
        }
       
        ////////////////////////////////////////////////////////////////////////
        // Get Start Date and Scroll Day/Month Variables 
        ////////////////////////////////////////////////////////////////////////
        if ( $is_matrix ) {                                      // MATRIX VIEW
            
            $bk_resources_id = explode( ',', $this->request_args['wh_booking_type'] );
            $max_rows_number = count( $bk_resources_id );

            switch ( $view_days_num ) {
                case '1':
                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = $this->request_args['scroll_day'];
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = date_i18n( "d" );                          //FixIn: 7.0.1.13
                    break;

                case '30':
                case '60':
                    if ( isset( $this->request_args['scroll_month'] ) )
                        $scroll_month = $this->request_args['scroll_month'];
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = 1;
                    break;

                case '7':                                                       // 7 Week - start from Monday (or other start week day)
                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = $this->request_args['scroll_day'];
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = date_i18n( "d" );
                    $start_week_day_num = date_i18n( "w" );
                    $start_day_weeek = get_bk_option( 'booking_start_day_weeek' ); //[0]:Sun .. [6]:Sut

                    if ( $start_week_day_num != $start_day_weeek ) {
                        for ( $d_inc = 1; $d_inc < 8; $d_inc++ ) {                // Just get week  back
                            
                            $real_date = mktime( 0, 0, 0, $start_month, ($start_day - $d_inc ), $start_year );

                            $start_week_day_num = date_i18n( "w", $real_date );
                            if ( $start_week_day_num == $start_day_weeek ) {
                                $start_day = date_i18n( "d", $real_date );
                                $start_year = date_i18n( "Y", $real_date );
                                $start_month = date_i18n( "m", $real_date );
                                $d_inc = 9;
                            }
                        }
                    }
                    break;

                default:  //30
                    if ( isset( $this->request_args['scroll_month'] ) )
                        $scroll_month = $this->request_args['scroll_month'];
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = 1;
                    break;
            }
            
        } else {                                                                // SINGLE Resource VIEW
            
            switch ( $view_days_num ) {
                case '90':
                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = $this->request_args['scroll_day'];
                    else
                        $scroll_day = 0;

                    $max_rows_number = 12;
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = date_i18n( "d" );
                    $start_week_day_num = date_i18n( "w" );
                    $start_day_weeek = get_bk_option( 'booking_start_day_weeek' ); //[0]:Sun .. [6]:Sut

                    if ( $start_week_day_num != $start_day_weeek ) {
                        for ( $d_inc = 1; $d_inc < 8; $d_inc++ ) {                // Just get week  back
                            $real_date = mktime( 0, 0, 0, $start_month, ($start_day - $d_inc ), $start_year );

                            $start_week_day_num = date_i18n( "w", $real_date );
                            if ( $start_week_day_num == $start_day_weeek ) {
                                $start_day = date_i18n( "d", $real_date );
                                $start_year = date_i18n( "Y", $real_date );
                                $start_month = date_i18n( "m", $real_date );
                                $d_inc = 9;
                            }
                        }
                    }
                    break;

                case '365':
                    if ( isset( $this->request_args['scroll_month'] ) )
                        $scroll_month = $this->request_args['scroll_month'];
                    else
                        $scroll_month = 0;
                    $max_rows_number = 12;
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = 1;
                    break;

                default:  // 30
                    if ( isset( $this->request_args['scroll_day'] ) )
                        $scroll_day = $this->request_args['scroll_day'];
                    else
                        $scroll_day = 0;

                    $max_rows_number = 31;
                    if ( empty( $this->request_args['scroll_start_date'] ) )
                        $start_day = date_i18n( "d" );                          //FixIn: 7.0.1.13
                    break;
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////
        
        ?><div class="wpbc_timeline_frame<?php if ( $this->is_frontend ) echo ' wpbc_timeline_front_end' ?>">
            <table class="wpbc_tl_table table table-striped" cellpadding="0" cellspacing="0">
                <tr class="wpbc_tl_table_header">
                    <?php 
                    if ( $this->is_frontend ) {
                        ?><th colspan="2" class="wpbc_tl_collumn_2"><?php 
                        
                            $title =  apply_bk_filter('wpdev_check_for_active_language', $this->timeline_titles['header_title'] );
                            
                            $params_nav = array();
                            $params_nav['title'] = $title;
                            $this->client_navigation( $params_nav );
                            
                        ?></th><?php
                        
                    } else {
                        ?>
                        <th class="wpbc_tl_collumn_1"><?php 
                                $title =  apply_bk_filter('wpdev_check_for_active_language', $this->timeline_titles['header_column1'] );
                                echo $title;          // Resources
                        ?></th>
                        <th class="wpbc_tl_collumn_2"><?php 
                            $title =  apply_bk_filter('wpdev_check_for_active_language', $this->timeline_titles['header_column2'] );
                            echo $title;              // Dates
                        ?></th>
                    <?php } ?>
                </tr>
                <tr class="wpbc_tl_table_row_colspan">
                    <td colspan="2"> </td>
                </tr>
                <tr class="wpbc_tl_table_titles">
                    <td class="wpbc_tl_collumn_1"></td>
                    <td class="wpbc_tl_collumn_2"><?php
                        // Header above the calendar table
                        $real_date = mktime( 0, 0, 0, ($start_month ), $start_day, $start_year );

                        if ( $is_matrix ) {    // MATRIX VIEW                    
                            switch ( $view_days_num ) {                                        // Set real start date for the each rows in calendar
                                case '1':
                                case '7':
                                    $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $scroll_day ), $start_year );
                                    break;

                                case '30':
                                case '60':
                                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), $start_day, $start_year );
                                    break;

                                default:  // 30
                                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), $start_day, $start_year );
                                    break;
                            }
                        } else {                            // Single Resource View
                            switch ( $view_days_num ) {                                        // Set real start date for the each rows in calendar
                                case '90':
                                    $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $scroll_day ), $start_year );
                                    break;

                                case '365':
                                    $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), $start_day, $start_year );
                                    break;

                                default:  // 30
                                    $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $scroll_day ), $start_year );
                                    break;
                            }
                        }

                        $this->wpbc_show_timeline_header_row( $real_date );
                        ?>
                    </td>
                </tr><?php
                
                for ( $d_inc = 0; $d_inc < $max_rows_number; $d_inc++ ) {

                    // Skip showing rows of booking resource(s) in TimeLine or Calendar Overview, if no any exist booking(s) for current view
                    if ( ! empty( $this->request_args['only_booked_resources'] ) ) {                         //FixIn: 7.0.1.51  

                        if ( $is_matrix ) $resource_id = $bk_resources_id[$d_inc];
                        else              $resource_id = $this->request_args['wh_booking_type'];  // Request from  GET or REQUEST

                        if ( ! in_array( $resource_id, $booked_booking_resources ) ) {
                            continue;
                        }
                    }                    
                    
                    
                    // Ger Start Date to real_date  variabale  /////////////////////
                    if ( $is_matrix ) {    // MATRIX VIEW                    
                        switch ( $view_days_num ) {                                        // Set real start date for the each rows in calendar
                            case '1':
                            case '7':
                                $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $scroll_day ), $start_year );
                                break;

                            case '30':
                            case '90':
                                $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), $start_day, $start_year );
                                break;

                            default:  // 30
                                $real_date = mktime( 0, 0, 0, ($start_month + $scroll_month ), $start_day, $start_year );
                                break;
                        }
                    } else {                            // Single Resource View
                        switch ( $view_days_num ) {                                        // Set real start date for the each rows in calendar
                            case '90':
                                $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $d_inc * 7 + $scroll_day ), $start_year );
                                break;

                            case '365':
                                $real_date = mktime( 0, 0, 0, ($start_month + $d_inc + $scroll_month ), $start_day, $start_year );
                                break;

                            default:  // 30
                                $real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $d_inc + $scroll_day ), $start_year );
                                break;
                        }
                    }
                    ////////////////////////////////////////////////////////////////
                    ?>
                    <tr class="wpbc_tl_table_row_bookings">
                        <td class="wpbc_tl_collumn_1"><?php
                            
                                // Title in first collumn of the each row in calendar //////
                                if ( ( $is_matrix ) && ( isset( $bk_resources_id[$d_inc] ) ) && (isset( $booking_types[$bk_resources_id[$d_inc]] )) ) {  // Matrix - resource titles
                                    
                                    $resource_value = $booking_types[$bk_resources_id[$d_inc]];
                                    $bk_admin_url = wpbc_get_params_in_url( wpbc_get_bookings_url( false, false ), array( 'wh_booking_type' ) );                                                                       
                                    
                                    ?><div class="wpbc_tl_resource_title <?php 
                                                    if ( isset( $resource_value->parent ) ) { if ( $resource_value->parent == 0 ) { echo 'parent'; } else { echo 'child'; } }
                                        ?> "><?php 
                                            if ( $this->is_frontend ) {

                                                if ( ( isset( $this->options['resource_link'] ) ) && ( isset( $this->options['resource_link'][ $resource_value->booking_type_id ] ) ) ){        //FixIn: 7.0.1.50
                                                    
													?><a href="<?php echo $this->options['resource_link'][ $resource_value->booking_type_id ]; ?>" ><?php //FixIn: 7.2.1.14
                                                }
												
                                                echo apply_bk_filter('wpdev_check_for_active_language', $resource_value->title );       //FixIn: 7.0.1.11
                                                
                                                if ( ( isset( $this->options['resource_link'] ) ) && ( isset( $this->options['resource_link'][ $resource_value->booking_type_id ] ) ) ){        //FixIn: 7.0.1.50  
                                                    ?></a><?php 
                                                }
                                            } else {
                                            ?><a href="<?php echo $bk_admin_url . '&wh_booking_type=' . $bk_resources_id[$d_inc]; ?>" /><?php 
                                                echo apply_bk_filter('wpdev_check_for_active_language', $resource_value->title ); 												
                                            ?></a><?php 
                                            }
                                  ?></div><?php
                                        
                                } else {    // Single Resource - Dates titles
                                    
                                    ?><div class="wpbc_tl_resource_title"><?php
                                    
                                    switch ( $view_days_num ) {
                                        case '90':
                                            $end_real_date = mktime( 0, 0, 0, $start_month, ( $start_day + $d_inc * 7 + $scroll_day ) + 6, $start_year );
                                            $date_format = ' j, Y'; //get_bk_option( 'booking_date_format');
                                            echo __( date_i18n( "M", $real_date ) ) . date_i18n( $date_format, $real_date ) . ' - ' . __( date_i18n( "M", $end_real_date ) ) . date_i18n( $date_format, $end_real_date );
                                            break;

                                        case '365':
                                            echo __( date_i18n( "F", $real_date ) ) . ', ' . date_i18n( "Y", $real_date );
                                            break;

                                        default:  // 30
                                            //$date_format = 'd / m / Y';
                                            $date_format = get_bk_option( 'booking_date_format' );                           //FixIn:5.4.5.13
                                            echo __( date_i18n( "D", $real_date ) ) . ', ' . date_i18n( $date_format, $real_date );
                                            break;
                                    }
                                    
                                    ?></div><?php      
                                }
                            ?>
                        </td>
                        <td  class="wpbc_tl_collumn_2">
                            <div class="wpbc_tl_dates_line"><?php
                            
                                if ( $is_matrix )    $resource_id = $bk_resources_id[$d_inc];
                                else                                $resource_id = $this->request_args['wh_booking_type'];  // Request from  GET or REQUEST

                                $this->wpbc_show_timeline_booking_row( 
                                                                        $resource_id
                                                                      , $real_date
                                                                      , array(
                                                                              $dates_array
                                                                              , $bookings
                                                                              , $booking_types
                                                                              , $time_array_new 
                                                                              ) 
                                                                      );
                            ?></div>
                        </td>
                    </tr><?php                    
                }

        ?></table></div><?php
    }


    ////////////////////////////////////////////////////////////////////////////
    // T O O L T I P 
    ////////////////////////////////////////////////////////////////////////////

    /**
	 * Get Booking Date for PopOver and inday cell text.
     * 
     * @param int $bk_id
     * @param array $bookings
     * @param array $booking_types
     * @param string $text_in_day_cell
     * @param string $header_title
     * @param string $content_text
     * 
     * @return array
     */
    public function wpbc_get_booking_info_4_tooltip( $bk_id, $bookings, $booking_types, $text_in_day_cell='', $header_title='', $content_text='' ){
        
		if ( isset( $bookings[ $bk_id ] ) ) {
			$bookings[ $bk_id ]->form_show = str_replace( "&amp;", '&', $bookings[ $bk_id ]->form_show );				//FixIn:7.1.2.12
		}

		$is_show_popover_in_timeline  = wpbc_is_show_popover_in_timeline( $this->is_frontend, $this->request_args['booking_hash'] );    	//FixIn: 8.1.3.5

        if ( count( $bookings[$bk_id]->dates ) > 0 )
             $is_approved = $bookings[$bk_id]->dates[0]->approved;
        else $is_approved = 0;
        
        
        ////////////////////////////////////////////////////////////////////////
        // Text in Day Cell 
        ////////////////////////////////////////////////////////////////////////
        if ( ! empty( $text_in_day_cell ) ) $text_in_day_cell .= ',';           // Other Booking in the same day
        
        if ( $this->is_frontend ) $what_show_in_day_template = get_bk_option( 'booking_default_title_in_day_for_timeline_front_end' );
        else                      $what_show_in_day_template = get_bk_option( 'booking_default_title_in_day_for_calendar_view_mode' );        
        
        if ( function_exists( 'get_title_for_showing_in_day' ) ) {
            $text_in_day_cell .= esc_textarea( get_title_for_showing_in_day( $bk_id, $bookings, $what_show_in_day_template ) );						//FixIn: 7.1.1.2
        } else {
            if ( ! $this->is_frontend ) 
                $text_in_day_cell .= $bk_id . ':' . esc_textarea( $bookings[$bk_id]->form_data['_all_fields_']['name'] );       // Default Free		//FixIn: 7.1.1.2
        }
        
        if ( ! $is_show_popover_in_timeline ) {
            return  array( 
                              $text_in_day_cell
                            , $text_in_day_cell//''    // $header_title
                            , ''    // $content_text
                            , $is_approved 
                    ) ;            
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // P O P O V E R 
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        ////////////////////////////////////////////////////////////////////////
        // Header 
        ////////////////////////////////////////////////////////////////////////
        if ( $header_title != '' ) {                                                   // Each new title of booking in the same day start from new line
            if ( ! $this->is_frontend )
                $header_title .= '<div class=\'clear\'></div>';                        // New line
            else
                $header_title .= '; ';                                                 // Comma separated
        }       
        $header_title .= '<div class=\'popover-title-id\' > ID: ' . $bk_id . '</div>'; // ID

//$header_title .= '<div class=\'popover-title-id\' > ' .
//(! empty($bookings[$bk_id]->form_data['_all_fields_']['name']) ? $bookings[$bk_id]->form_data['_all_fields_']['name'] : '' ) . ' '
//. (! empty($bookings[$bk_id]->form_data['_all_fields_']['secondname']) ? $bookings[$bk_id]->form_data['_all_fields_']['secondname'] : '' )
//. '</div>'; // ID


        // Buttons
        $header_title .= '<div class=\'control-group timeline_info_bk_actionsbar_' . $bk_id . '\' >';
        $is_can = true; //current_user_can( 'edit_posts' );
        if ( ( ! $this->is_frontend ) && ( $is_can ) ) {
            // Link
            $header_title .= '<a class=\'button button-secondary\' href=\''.wpbc_get_bookings_url( true, false ).'&wh_booking_id='.$bk_id.'&view_mode=vm_listing&tab=actions\' ><i class=\'glyphicon glyphicon-screenshot\'></i></a>';
            //Edit
            if ( class_exists( 'wpdev_bk_personal' ) ) {
                $bk_url_add = wpbc_get_new_booking_url( true, false );
                $bk_hash = (isset( $bookings[$bk_id]->hash )) ? $bookings[$bk_id]->hash : '';
                $bk_booking_type = $bookings[$bk_id]->booking_type;
                $edit_booking_url = $bk_url_add . '&booking_type=' . $bk_booking_type . '&booking_hash=' . $bk_hash . '&parent_res=1';
                $header_title .= '<a class=\'button button-secondary\' href=\'' . $edit_booking_url . '\' onclick=\'\' ><i class=\'glyphicon glyphicon-edit\'></i></a>';
            
                // Print
                if ( class_exists( 'wpdev_bk_biz_s' ) ) 
                    $header_title .= '<a href=\'javascript:void(0)\' 
                         onclick=\'javascript: wpbc_print_specific_booking_for_timeline( '.$bk_id.' );\'
                         class=\'tooltip_top button-secondary button\'
                     ><i class=\'glyphicon glyphicon-print\'></i></a>';

                $header_title .= '<span class=\'wpbc-buttons-separator\'></span>';
            }
            // Trash
            //$header_title .= '<a class=\'button button-secondary\' href=\'javascript:;\' onclick=\'javascript:delete_booking(' . $bk_id . ', ' . $this->current_user_id . ', &quot;' . wpbc_get_booking_locale() . '&quot; , 1   );\' ><i class=\'glyphicon glyphicon-trash\'></i></a>';
            //FixIn: 6.1.1.10 
            $is_trash = $bookings[$bk_id]->trash;  
            // Trash                        
            $header_title .= '<a class=\'button button-secondary trash_bk_link'.(( $is_trash)?' hidden_items ':'').'\'  href=\'javascript:;\' onclick=\'javascript:trash__restore_booking(1,' . $bk_id . ', ' . $this->current_user_id . ', &quot;' . wpbc_get_booking_locale() . '&quot; , 1   );\' ><i class=\'glyphicon glyphicon-trash\'></i></a>';
            // Restore
            $header_title .= '<a class=\'button button-secondary restore_bk_link'.((!$is_trash)?' hidden_items ':'').'\'  href=\'javascript:;\' onclick=\'javascript:trash__restore_booking(0,' . $bk_id . ', ' . $this->current_user_id . ', &quot;' . wpbc_get_booking_locale() . '&quot; , 1   );\' ><i class=\'glyphicon glyphicon-repeat\'></i></a>';
            // Delete
            $header_title .= '<a class=\'button button-secondary delete_bk_link'.((!$is_trash)?' hidden_items ':'').'\'  href=\'javascript:;\' onclick=\'javascript:delete_booking(' . $bk_id . ', ' . $this->current_user_id . ', &quot;' . wpbc_get_booking_locale() . '&quot; , 1   );\' ><i class=\'glyphicon glyphicon-remove\'></i></a>';
            //End FixIn: 6.1.1.10 

            // Approve | Decline
            $header_title .= '<a class=\'button button-secondary approve_bk_link ' . ($is_approved ? 'hidden_items' : '') . '\' href=\'javascript:;\' onclick=\'javascript:approve_unapprove_booking(' . $bk_id . ',1, ' . $this->current_user_id . ', &quot;' . wpbc_get_booking_locale() . '&quot; , 1   );\' ><i class=\'glyphicon glyphicon-ok-circle\'></i></a>';
            $header_title .= '<a class=\'button button-secondary pending_bk_link ' . ($is_approved ? '' : 'hidden_items') . '\' href=\'javascript:;\' onclick=\'javascript:approve_unapprove_booking(' . $bk_id . ',0, ' . $this->current_user_id . ', &quot;' . wpbc_get_booking_locale() . '&quot; , 1   );\' ><i class=\'glyphicon glyphicon-ban-circle\'></i></a>';
            
            
        }

        else if ( ( $this->is_frontend ) && ( ! empty( $this->request_args['booking_hash'] ) ) ) {							//FixIn: 8.1.3.5
        	// Valid or not valid hasg  we was checked at begining of function.

            //Edit
            if ( class_exists( 'wpdev_bk_personal' ) ) {

				// $edit_booking_url_admin = wpbc_get_bookings_url( true, false ).'&wh_booking_id='.$bk_id.'&view_mode=vm_listing&tab=actions';
				// $trash_booking_url_admin = wpbc_get_bookings_url( true, false ).'&wh_booking_id='.$bk_id.'&view_mode=vm_listing&tab=actions';

				if ( ( ! $is_approved ) || ( true ) ) {                                                                 //FixIn: 8.2.1.14
					$visitorbookingediturl   = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingediturl]', $bk_id );
					$visitorbookingcancelurl = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingcancelurl]', $bk_id );
					$visitorbookingpayurl    = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingpayurl]', $bk_id );

					$header_title .= '<a class=\'btn btn-default wpbc_btn_in_timeline\' title=\''. esc_js( __( 'Edit', 'booking' ) ).'\' href=\'' . $visitorbookingediturl . '\' ><i class=\'glyphicon glyphicon-edit\'></i></a>';
					$header_title .= '<a class=\'btn btn-default wpbc_btn_in_timeline\' title=\''. esc_js( __( 'Decline', 'booking' ) ).'\'  href=\'' . $visitorbookingcancelurl . '\' ><i class=\'glyphicon glyphicon-trash\'></i></a>';
					$header_title .= '<a class=\'btn btn-default wpbc_btn_in_timeline\' title=\''. esc_js( __( 'Pay', 'booking' ) ).'\'  href=\'' . $visitorbookingpayurl . '\' ><i class=\'glyphicon glyphicon-credit-card\'></i></a>';
				}
            }
        }

        $header_title .= '</div>';
        
        
        ////////////////////////////////////////////////////////////////////////
        // Content 
        ////////////////////////////////////////////////////////////////////////
        if ( $content_text != '' ) $content_text .= ' <hr class="wpbc_tl_popover_booking_separator" /> ';       // Separate Other Booking in the same day

        // Container
        $content_text .= '<div id=\'wpbc-booking-id-'.$bk_id.'\' class=\'wpbc-listing-collumn wpbc-popover-content-data\' >';

        
        
        $content_text .= '<div class=\'wpbc-popover-labels-bar\' >';
            // ID
            //$content_text .= '<span class=\'field-id\'>' . $bk_id . '</span>';

            $content_text .= '<div class=\'field-id text-center\'>';
            $content_text .= '<span class=\'label\'>' . $bk_id . '</span>';
            $content_text .= '</div>';

            // Labels
//            $content_text .= '<div class=\'text-left field-labels booking-labels\'>'; 
//            $content_text .= '<span class=\'label label-default label-pending' . ( $is_approved ? ' hidden_items' : '' ) . '\'>' . __('Pending' ,'booking') . '</span>';
//            $content_text .= '<span class=\'label label-default label-approved' . ( !$is_approved ? ' hidden_items' : '' ) . '\'>' . __('Approved' ,'booking') . '</span>';            
//            $content_text .= '</div>';
            

            
            // Resource
            if ( function_exists( 'get_booking_title' ) ) {

                if ( isset( $booking_types[$bookings[$bk_id]->booking_type] ) )     $bk_title = $booking_types[$bookings[$bk_id]->booking_type]->title;
                else                                                                $bk_title = get_booking_title( $bookings[$bk_id]->booking_type );

                $content_text .= '<div class=\'text-left field-labels booking-labels\'>';            
                $content_text .= '<span class=\'label label-default label-resource label-info\'>' . esc_textarea( $bk_title ) . '</span>';	//FixIn: 7.1.1.2
                $content_text .= '</div>';
            } 

    
            // Payment Status
            if ( class_exists( 'wpdev_bk_biz_s' ) ) {

                if ( function_exists( 'wpdev_bk_get_payment_status_simple' ) ) {                
                    $pay_status = wpdev_bk_get_payment_status_simple( $bookings[$bk_id]->pay_status );                
                    $content_text .= '<div class=\'text-left field-labels booking-labels\'>';    
                    if ( wpbc_is_payment_status_ok( trim( $bookings[$bk_id]->pay_status ) ) )
                        $content_text .= '<span class=\'label label-default label-payment-status payment-label-success\'><span class=\'label-payment-status-prefix\'>' . esc_js( __( 'Payment', 'booking' ) ). '</span> ' . esc_js( $pay_status ) . '</span>';		//FixIn: 7.1.1.3
                    else {
                    	if (  floatval( $bookings[ $bk_id ]->cost ) > 0 ) {                								//FixIn: 8.3.3.9
		                    $content_text .= '<span class=\'label label-default label-payment-status payment-label-unknown\'><span class=\'label-payment-status-prefix\'>' . esc_js( __( 'Payment', 'booking' ) ) . '</span> ' . esc_js( $pay_status ) . '</span>';        //FixIn: 7.1.1.3
	                    }
                    }
                    $content_text .= '</div>';                
                } 


            }
        $content_text .= '</div>';          
        
        if ( ( class_exists( 'wpdev_bk_biz_s' ) ) ) {	//&& ( ! $this->is_frontend )  ){

        	//FixIn: 8.3.3.9
	        if ( floatval( $bookings[ $bk_id ]->cost ) > 0 ) {
		        // Cost
		        $booking_cost = wpbc_get_cost_with_currency_for_user( $bookings[ $bk_id ]->cost, $bookings[ $bk_id ]->booking_type );

		        $content_text .= '<div class=\'wpbc-popover-cost-bar\' >';
		        //$content_text .= '<div class=\'text-left field-labels booking-labels\'>';
			        $content_text .= '<div class=\'label0 label-default0 wpbc-popover-cost\'>' . $booking_cost . '</div>';
		        //$content_text .= '</div>';
		        $content_text .= '</div>';
	        }
        }

        if ( ! $this->is_frontend ) {
            // Trash
            $content_text .= '<div class=\'text-left field-labels booking-labels\'>'; 
            $content_text .= '<span class=\'label label-trash label-danger' . ( ( ! $bookings[$bk_id]->trash ) ? ' hidden_items ' : '' ) . '\'>' . esc_js( __('Trash' ,'booking') ) . '</span>';    //FixIn: 6.1.1.10 //FixIn: 7.1.1.3
            $content_text .= '</div>';
        }
        
        
        $content_text .= '<div class=\'clear\'></div>';                         // New line
        
        // Booking Data
        $content_text .= '<div class=\'wpbc-popover-booking-data\'>' . esc_textarea( $bookings[$bk_id]->form_show ) . '</div>'; //FixIn: 7.1.1.2
        
        $content_text .= '<div class=\'clear\'></div>';                         // New line
        
        
        // Notes 
        if ( ! empty( $bookings[$bk_id]->remark ) ) {        
            $content_text .= '<div class=\'wpbc-popover-booking-notes\'>' . '<strong>' . esc_js( __('Note', 'booking') ). ':</strong> ' . esc_textarea( $bookings[$bk_id]->remark ) . '</div>'; //FixIn: 7.1.1.2		//FixIn: 7.1.1.3
            $content_text .= '<div class=\'clear\'></div>';                     // New line            
        }
        
        // Dates
        $bk_dates_short_id = array();                                           //BL
        if ( count( $bookings[$bk_id]->dates ) > 0 )
            $bk_dates_short_id = (isset( $bookings[$bk_id]->dates_short_id )) ? $bookings[$bk_id]->dates_short_id : array();      // Array ([0] => [1] => .... [4] => 6... [11] => [12] => 8 )
        
        $short_dates_content = wpbc_get_short_dates_formated_to_show( $bookings[$bk_id]->dates_short, $is_approved, $bk_dates_short_id, $booking_types );
        $short_dates_content = str_replace( '"', "'", $short_dates_content );

        $content_text .= '<div class=\'text-left field-dates booking-dates \'>';            
        $content_text .= '<div class=\'booking_dates_small\'>' . $short_dates_content . '</div>';
        $content_text .= '</div>';                
        
        $content_text .= '</div>';

//        if (  ( $this->is_frontend ) && ( ! $is_approved )  ) {
//        	$text_in_day_cell = '';
//		}
        return  array( 
                          $text_in_day_cell
                        , $header_title
                        , $content_text
                        , $is_approved 
                ) ;
    }        
    
}



/** Navigation of Timeline in Ajax request */
function wpbc_ajax_timeline() {    
    /*
     [timeline_obj] => Array
                (
                    [is_frontend] => 1
                    [html_client_id] => wpbc_timeline_1454680376080
                    [wh_booking_type] => 3,4,1,5,6,7,8,9,2,10,11,12,14
                    [is_matrix] => 1
                    [view_days_num] => 30
                    [scroll_start_date] => 
                    [scroll_day] => 0
                    [scroll_month] => 0
                )
     */
    
    
    $attr = $_POST['timeline_obj'];
    $attr['nav_step'] = $_POST['nav_step'];
    
    ob_start();

    $timeline = new WPBC_Timeline();

    $html_client_id = $timeline->ajax_init( $attr );                            // Define arameters and get bookings
//debuge($timeline->options);            
    
    //echo '<div class="wpbc_timeline_ajax_replace">';                          // Replace content of this container
        $timeline->show_timeline();


        $is_show_popover_in_timeline  = wpbc_is_show_popover_in_timeline( $attr['is_frontend'], $attr['booking_hash'] );    	//FixIn: 8.1.3.5

        if ( $is_show_popover_in_timeline ) {                                   // Update New Popovers
            
            ?><script type="text/javascript">   
                    if ( jQuery.isFunction( jQuery(".popover_click.popover_bottom" ).popover )  ) {      //FixIn: 7.0.1.2  - 2016-12-10
                        jQuery('.popover_click.popover_bottom').popover( {
                              placement: 'bottom auto'
                            , trigger:'manual'  
                            //, delay: {show: 100, hide: 8}
                            , content: ''
                            , template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-close"><a href="javascript:void(0)" data-dismiss="popover" aria-hidden="true">&times;</a></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
                            , container: '.wpbc_timeline_frame'
                            , html: 'true'
                        });      
                    }
            </script><?php 
        }
    //echo '</div>'; 

            
    $timeline_results = ob_get_contents();

    ob_end_clean();

    echo  $timeline_results ;    
}
add_bk_action('wpbc_ajax_timeline', 'wpbc_ajax_timeline');



/** Check if we are showing booking details or not
 *  Admin panel - always show
 *  Timeline - show if activated setting option
 *  Customer listing - show always,  if valid hash.
 *
 * @param $is_frontend
 * @param $booking_hash
 *
 * @return bool
 */
function wpbc_is_show_popover_in_timeline( $is_frontend, $booking_hash ){

	// Default for admin
	$is_show_popover_in_timeline = true;

	// For client Timeline
	if ( $is_frontend )
		$is_show_popover_in_timeline  =  ( get_bk_option( 'booking_is_show_popover_in_timeline_front_end' ) == 'On' ) ? true : false ;

	// For customer booking listing with  ability to  edit
	//FixIn: 8.1.3.5
	if ( ( $is_frontend ) && ( ! empty( $booking_hash ) ) ) {

		//In case if we have valid valid hash  then  show booking details
		$my_booking_id_type = apply_bk_filter( 'wpdev_booking_get_hash_to_id', false, $booking_hash );

		if ( ! empty( $my_booking_id_type ) ) {
			$is_show_popover_in_timeline = true;
		} else {
			$is_show_popover_in_timeline = false;
		}
	}
	return $is_show_popover_in_timeline;
}