<?php
	/**
	 * Copyright (c) 2016.
	 * Author  KGISL - IAS
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Licensed under The MIT License
	 * Redistributions of files must strictly Prohibited.
	 * Copyright Copyright (c) KGISL. (http://www.kgisl.com)
	 * link http://ias.kgisl.com KGISL IAS - Unit of KGISL's Project
	 */

	namespace App\Controller;

	use Cake\Core\Configure;
	use Cake\Network\Exception\NotFoundException;
	use Cake\View\Exception\MissingTemplateException;
	use Cake\View\Helper\SessionHelper;
	use Cake\Datasource\ConnectionManager;
	use Cake\ORM\TableRegistry;

	class DashboardController extends AppController {
		public function initialize() {
			parent::initialize();

			$this->session = $this->request->session();
			$this->model   = $this->Custom->include_models( 'bookings,vehicle,vehicle_attendance' );
			$this->conn    = ConnectionManager::get( 'default' );
		}

		public function freeTaxi() {
			if ( $this->is_ajax ) {
				$conn        = ConnectionManager::get( 'default' );
				$month_first = date( 'Y-m-01' );
				$sql         = "SELECT v.id, v.vehicle_no, vt.last_trip_time, mc.month_collection, b.today_empty_km, b.total_km, b.today_trips, b.today_collection, vt.current_place, v.running_status
		    FROM vehicle AS v
		    LEFT JOIN vehicle_temp AS vt ON vt.vehicle_id = v.id
		    LEFT JOIN (SELECT taxi_id, COUNT(id) AS trips, SUM(total_amount) AS month_collection FROM bookings WHERE requested_pickup_time::DATE >= '{$month_first}' GROUP BY taxi_id) AS mc ON mc.taxi_id = v.id
		    LEFT JOIN (SELECT taxi_id, COUNT(id) AS today_trips, SUM(total_km) AS total_km, SUM(total_amount) AS today_collection, SUM(empty_km) AS today_empty_km FROM bookings WHERE requested_pickup_time::DATE = CURRENT_DATE GROUP BY taxi_id) AS b ON b.taxi_id = v.id
		    LEFT JOIN (SELECT taxi_id, COUNT(id) AS trips FROM bookings WHERE trip_status = 'assigned' AND requested_pickup_time >= CURRENT_TIMESTAMP - INTERVAL '10 days' GROUP BY taxi_id) AS ba ON ba.taxi_id = v.id
		    WHERE v.status = 'active' AND v.running_status IN ('free', 'break') AND (ba.trips = 0 OR ba.trips IS NULL)
			ORDER BY vt.last_trip_time ASC
			";

				$qry = $conn->execute( $sql );

				$iTotal         = $qry->rowCount();
				$iFilteredTotal = $iTotal;
				$output         = array(
					"iTotalRecords"        => $iTotal,
					"iTotalDisplayRecords" => $iFilteredTotal,
					"aaData"               => array(),
				);

				$i = 0;
				foreach ( $qry->fetchAll( 'assoc' ) as $veh ) {
					$i ++;
					$sub_qry = "(SELECT vl.id,vl.name AS attence_loc, va.in_time AS login_time, 0 AS previous_balance 
		    	            FROM vehicle_attendance AS va 
							LEFT JOIN locations AS vl ON vl.id = va.atten_loc::INT 
							WHERE vehicle_id = {$veh['id']} ORDER BY in_time DESC LIMIT 1)
							UNION
							(SELECT vehicle_id,'' AS attence_loc, NULL AS login_time, previous_balance FROM cc_received 
							WHERE vehicle_id = {$veh['id']} ORDER BY created_at DESC LIMIT 1)";

					$login_days = $conn->execute( "SELECT COUNT(DISTINCT in_time::DATE) AS login_days FROM vehicle_attendance WHERE vehicle_id = {$veh['id']} AND in_time::DATE >= '{$month_first}'" )->fetchAll( 'assoc' )[0]['login_days'];

					$sub_exec                = $conn->execute( $sub_qry );
					$sres                    = $sub_exec->fetchAll( 'assoc' );
					if(isset($sres[0]['attence_loc']))
						$veh['attence_loc']      = substr($sres[0]['attence_loc'],12);
					else
						$veh['attence_loc']      = "";
					if(isset($sres[1]['login_time']) && !empty($sres[1]['login_time']))
						$veh['login_time']       = date( 'h:i A', strtotime( $sres[1]['login_time'] ) );
					else if(isset($sres[0]['login_time']) && !empty($sres[0]['login_time']))
						$veh['login_time']       = date( 'h:i A', strtotime( $sres[0]['login_time'] ) );
					else
						$veh['login_time']="";
					if(isset($sres[0]['previous_balance']))
						$veh['previous_balance'] = $sres[0]['previous_balance'];
					else
						$veh['previous_balance']="";
					$veh['login_days']       = $login_days;
					
					if(isset($veh['current_place']) && !empty($veh['current_place']))
					{
						$veh['current_place_full'] = $veh['current_place'];
						$veh['current_place'] = substr($veh['current_place'],0,15);
					}

					$now       = new \DateTime();
					$then      = new \DateTime( $veh['last_trip_time'] );
					$idle_time = $now->diff( $then );

					$veh['idle_time']   = $idle_time->format( '%H:%I' );
					$veh['sno']         = $i;
					$output['aaData'][] = $veh;
				}

				print_r( json_encode( $output ) );
				exit;
			}
		}

		public function vehicleStatistics() {
			if ( $this->is_ajax ) {
				$join     = array(
					'vt' => array(
						'table'      => 'vehicle_temp',
						'type'       => "LEFT",
						'conditions' => 'vehicle.id = vt.vehicle_id'
					),
					'd'  => array(
						'table'      => 'users',
						'type'       => "LEFT",
						'conditions' => 'vehicle.current_driver = d.id'
					)
				);
				$qry      = $this->model['vehicle']->select( [
					'vehicle.id',
					'vehicle_no',
					'vt.running_status',
					'vt.current_place',
					'd.display_name'
				] )->join( $join )->where( [ 'vehicle.status' => 'active' ] )->hydrate( false );
				$vehicles = array();
				foreach ( $qry->toArray() as $veh ) {
					$vam                = $this->Custom->include_models( 'vehicle_attendance' );
					$vaq                = $vam['vehicle_attendance']->select( [
						'vehicle_id',
						'out_time'
					] )->where( [ 'vehicle_id' => $veh['id'] ] )->order( [ 'in_time' => 'DESC' ] )->limit( 1 )->hydrate( false )->toArray();
					$veh['last_logout'] = ! empty( $vaq[0]['out_time'] ) ? $vaq[0]['out_time']->format( 'd/m/Y h:i A' ) : '';
					$vehicles[]         = $veh;
				}
				print_r( json_encode( $vehicles ) );
				exit;
			}
		}
	}