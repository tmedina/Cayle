<?

####################
# diffFuncs.inc
#
# history:
# 07-10-06  jobnacct  Nortel GSM Dropped Cell
# 07-11-06  jobnacct  Lucent Dropped Cell
# 07-12-06  jobnacct  GPRS Dropped Cell

####################
#  Sizes of each
#  type of structure
#
$structSize = array( 945,     # 0 udrsp v1.0
                     1394,    # 1 udr v1.0 (pipe) [change udr v1.0 non-pipe also]
                     1649,    # 2 udr v2.0 (pipe) [change udr v2.0 non-pipe also]
                     200,     # 3 CALCDR
                     300,     # 4 DATCDR
                     300,     # 5 SMSCDR
                     1394,    # 6 udr v1.0  [change in sync with v1.0 pipe]
                     1649,    # 7 udr v2.0  [change in sync with v2.0 pipe]
                     1243,    # 8 udrsp v2.0
                     1277,    # 9 udrch
                     250,     # 10 CALCDR 2
                     190,     # 11 Cingular Fixed
                     567,     # 12 Ciber Type 22
                     1194,    # 13 UDRSD file
                     853,     # 14 nt_ng (ngstruct.h)
                     716,     # 15 ntgsm_rec (ntgsmstruct.h)          # 07-10-06
                     890,     # 16 lucfmtrec (lucfmtstructure.h)      # 07-11-06
                     1176     # 17 gprs_opn020                        # 07-12-06
                     );       #

##############################
#  Debug flag
#  (used to turn on verbose
#  data dumping)
#  
$debug = 0;

#################
#  UDRSP Struct
#
$udrspStruct = array( array( 'udrsp_type_id', 5, 'AN' ),
                      array( 'udrsp_creation_datetime', 12, 'AN' ),
                      array( 'udrsp_air_amount', 11, 'AN' ),
                      array( 'udrsp_air_local_tax', 11, 'AN' ),
                      array( 'udrsp_air_state_tax', 11, 'AN' ),
                      array( 'udrsp_air_rate_period', 2, 'AN' ),
                      array( 'udrsp_apn_network_info', 20, 'AN' ),
                      array( 'udrsp_apn_operator_info', 20, 'AN' ),
                      array( 'udrsp_apn_selection_mode', 20, 'AN' ),
                      array( 'udrsp_billed_eid', 12, 'AN' ),
                      array( 'udrsp_billed_esn', 15, 'AN' ),
                      array( 'udrsp_billed_imei', 15, 'AN' ),
                      array( 'udrsp_billed_imsi', 15, 'AN' ),
                      array( 'udrsp_billed_mdn_msisd', 10, 'AN' ),
                      array( 'udrsp_billed_min', 10, 'AN' ),
                      array( 'udrsp_billed_celltower', 10, 'AN' ),
                      array( 'udrsp_call_end_reason', 2, 'AN' ),
                      array( 'udrsp_call_identifier_block', 10, 'AN' ),
                      array( 'udrsp_call_identifier_rcd_cnt', 5, 'AN' ),
                      array( 'udrsp_called_ton', 6, 'AN' ),
                      array( 'udrsp_caller_id', 10, 'AN' ),
                      array( 'udrsp_caller_id_block', 1, 'AN' ),
                      array( 'udrsp_charge_in_packets', 10, 'AN' ),
                      array( 'udrsp_charge_in_units', 10, 'AN' ),
                      array( 'udrsp_charge_out_packets', 10, 'AN' ),
                      array( 'udrsp_charge_out_units', 10, 'AN' ),
                      array( 'udrsp_charge_packets', 10, 'AN' ),
                      array( 'udrsp_charging_characteristics', 1, 'AN' ),
                      array( 'udrsp_charging_info', 15, 'AN' ),
                      array( 'udrsp_ciber_type', 2, 'AN' ),
                      array( 'udrsp_coord_h', 5, 'AN' ),
                      array( 'udrsp_coord_v', 5, 'AN' ),
                      array( 'udrsp_currency', 2, 'AN' ),
                      array( 'udrsp_datetime_end', 16, 'AN' ),
                      array( 'udrsp_datetime_connect', 16, 'AN' ),
                      array( 'udrsp_datetime_ring', 16, 'AN' ),
                      array( 'udrsp_datetime_start', 16, 'AN' ),
                      array( 'udrsp_daylight_sav', 1, 'AN' ),
                      array( 'udrsp_description', 30, 'AN' ),
                      array( 'udrsp_discard_in_units', 9, 'AN' ),
                      array( 'udrsp_discard_out_units', 9, 'AN' ),
                      array( 'udrsp_dst_city', 10, 'AN' ),
                      array( 'udrsp_dst_country', 4, 'AN' ),
                      array( 'udrsp_dst_dialed', 32, 'AN' ),
                      array( 'udrsp_dst_imsi', 15, 'AN' ),
                      array( 'udrsp_dst_lrn', 10, 'AN' ),
                      array( 'udrsp_dst_mdn', 10, 'AN' ),
                      array( 'udrsp_dst_min', 10, 'AN' ),
                      array( 'udrsp_dst_state', 2, 'AN' ),
                      array( 'udrsp_duration', 8, 'AN' ),
                      array( 'udrsp_duration_ring', 8, 'AN' ),
                      array( 'udrsp_duration_connect', 8, 'AN' ),
                      array( 'udrsp_duration_unit', 2, 'AN' ),
                      array( 'udrsp_billed_market', 3, 'AN' ),
                      array( 'udrsp_market_npa', 6, 'AN' ),
                      array( 'udrsp_event', 3, 'AN' ),
                      array( 'udrsp_file_reference', 11, 'AN' ),
                      array( 'udrsp_ggsn_address_used', 20, 'AN' ),
                      array( 'udrsp_home_pmn', 5, 'AN' ),
                      array( 'udrsp_home_rmr_rerate', 1, 'AN' ),
                      array( 'udrsp_home_sid', 5, 'AN' ),
                      array( 'udrsp_interconnect', 2, 'AN' ),
                      array( 'udrsp_interconnect_amount', 11, 'AN' ),
                      array( 'udrsp_interconnect_flag', 1, 'AN' ),
                      array( 'udrsp_interconnect_toll', 1, 'AN' ),
                      array( 'udrsp_jip', 10, 'AN' ),
                      array( 'udrsp_ld_route', 10, 'AN' ),
                      array( 'udrsp_local_rec_seq_num', 9, 'AN' ),
                      array( 'udrsp_message_length', 7, 'AN' ),
                      array( 'udrsp_toll_mileage', 5, 'AN' ),
                      array( 'udrsp_ms_network_capability', 2, 'AN' ),
                      array( 'udrsp_network_initiation', 2, 'AN' ),
                      array( 'udrsp_no_air_flag', 1, 'AN' ),
                      array( 'udrsp_other_amount', 11, 'AN' ),
                      array( 'udrsp_other_local_tax', 11, 'AN' ),
                      array( 'udrsp_other_state_tax', 11, 'AN' ),
                      array( 'udrsp_price', 11, 'AN' ),
                      array( 'udrsp_rate_center', 5, 'AN' ),
                      array( 'udrsp_served_market', 3, 'AN' ),
                      array( 'udrsp_served_pmn', 5, 'AN' ),
                      array( 'udrsp_served_sid', 5, 'AN' ),
                      array( 'udrsp_served_pdp_addr', 20, 'AN' ),
                      array( 'udrsp_service_feature', 2, 'AN' ),
                      array( 'udrsp_service_sub_feature', 4, 'AN' ),
                      array( 'udrsp_src_city', 10, 'AN' ),
                      array( 'udrsp_src_country', 4, 'AN' ),
                      array( 'udrsp_src_infra_hw_info', 20, 'AN' ),
                      array( 'udrsp_src_lrn', 10, 'AN' ),
                      array( 'udrsp_src_npa_nxx', 6, 'AN' ),
                      array( 'udrsp_src_state', 2, 'AN' ),
                      array( 'udrsp_surcharge_amount', 11, 'AN' ),
                      array( 'udrsp_surcharge_info', 2, 'AN' ),
                      array( 'udrsp_surcharge_misc_amount', 11, 'AN' ),
                      array( 'udrsp_surcharge_misc_info', 2, 'AN' ),
                      array( 'udrsp_switch_file_batch', 20, 'AN' ),
                      array( 'udrsp_switch_file', 10, 'AN' ),
                      array( 'udrsp_timezone_incr_done', 1, 'AN' ),
                      array( 'udrsp_tldn', 10, 'AN' ),
                      array( 'udrsp_toll_amount', 11, 'AN' ),
                      array( 'udrsp_toll_carrier', 5, 'AN' ),
                      array( 'udrsp_toll_local_tax', 11, 'AN' ),
                      array( 'udrsp_toll_multi_rate', 1, 'AN' ),
                      array( 'udrsp_toll_period', 2, 'AN' ),
                      array( 'udrsp_toll_spec_type', 2, 'AN' ),
                      array( 'udrsp_toll_state_tax', 11, 'AN' ),
                      array( 'udrsp_toll_tarif', 2, 'AN' ),
                      array( 'udrsp_toll_tax', 1, 'AN' ),
                      array( 'udrsp_toll_wide_area', 3, 'AN' ),
                      array( 'udrsp_trunk_group_incoming', 10, 'AN' ),
                      array( 'udrsp_trunk_group_outgoing', 10, 'AN' ),
                      array( 'udrsp_wnp_dip_charge_info', 4, 'AN' ),
                      array( 'udrsp_wnp_dip_charge_ind', 1, 'AN' ),
                      array( 'udrsp_world_timezone', 2, 'AN' ),
                      array( 'udrsp_zero_ld_chg_flag', 1, 'AN' ),
                      array( 'newline', 1, 'AN' ) );

#################
#  UDRSP Struct
#
$udrsp2Struct = array( array( 'udrsp_type_id', 5, 'AN' ),
                       array( 'udrsp_phase_0_datetime', 14, 'AN' ),
                       array( 'udrsp_phase_0_result', 16, 'AN' ),
                       array( 'udrsp_phase_0_status', 2, 'AN' ),
                       array( 'udrsp_phase_3_status', 2, 'AN' ),
                       array( 'udrsp_creator', 15, 'AN' ),
                       array( 'udrsp_creation_datetime', 12, 'AN' ),
                       array( 'udrsp_air_amount', 11, 'AN' ),
                       array( 'udrsp_air_local_tax', 11, 'AN' ),
                       array( 'udrsp_air_state_tax', 11, 'AN' ),
                       array( 'udrsp_air_rate_period', 2, 'AN' ),
                       array( 'udrsp_apn_network_info', 20, 'AN' ),
                       array( 'udrsp_apn_operator_info', 20, 'AN' ),
                       array( 'udrsp_apn_selection_mode', 20, 'AN' ),
                       array( 'udrsp_billed_eid', 12, 'AN' ),
                       array( 'udrsp_billed_esn', 15, 'AN' ),
                       array( 'udrsp_billed_imei', 15, 'AN' ),
                       array( 'udrsp_billed_imsi', 15, 'AN' ),
                       array( 'udrsp_billed_mdn_msisd', 16, 'AN' ),
                       array( 'udrsp_billed_esn_meid', 20, 'AN' ),
                       array( 'udrsp_billed_meid_chk_digit', 2, 'AN' ),
                       array( 'udrsp_billed_min', 10, 'AN' ),
                       array( 'udrsp_billed_sim', 20, 'AN' ),
                       array( 'udrsp_billed_celltower', 10, 'AN' ),
                       array( 'udrsp_call_end_reason', 2, 'AN' ),
                       array( 'udrsp_call_identifier_block', 10, 'AN' ),
                       array( 'udrsp_call_identifier_rcd_cnt', 5, 'AN' ),
                       array( 'udrsp_called_ton', 6, 'AN' ),
                       array( 'udrsp_caller_id', 16, 'AN' ),
                       array( 'udrsp_caller_id_block', 1, 'AN' ),
                       array( 'udrsp_camel_service_key', 10, 'AN' ),
                       array( 'udrsp_camel_service_level', 1, 'AN' ),
                       array( 'udrsp_camel_dest_number', 32, 'AN' ),
                       array( 'udrsp_camel_invocation_fee', 11, 'AN' ),
                       array( 'udrsp_camel_tax', 11, 'AN' ),
                       array( 'udrsp_change_condition', 1, 'AN' ),
                       array( 'udrsp_change_time', 12, 'AN' ),
                       array( 'udrsp_charge_in_packets', 10, 'AN' ),
                       array( 'udrsp_charge_in_units', 10, 'AN' ),
                       array( 'udrsp_charge_out_packets', 10, 'AN' ),
                       array( 'udrsp_charge_out_units', 10, 'AN' ),
                       array( 'udrsp_charge_packets', 10, 'AN' ),
                       array( 'udrsp_charging_characteristics', 1, 'AN' ),
                       array( 'udrsp_charging_info', 15, 'AN' ),
                       array( 'udrsp_ciber_type', 2, 'AN' ),
                       array( 'udrsp_coord_h', 5, 'AN' ),
                       array( 'udrsp_coord_v', 5, 'AN' ),
                       array( 'udrsp_currency', 2, 'AN' ),
                       array( 'udrsp_datetime_end', 16, 'AN' ),
                       array( 'udrsp_datetime_connect', 16, 'AN' ),
                       array( 'udrsp_datetime_ring', 16, 'AN' ),
                       array( 'udrsp_datetime_start', 16, 'AN' ),
                       array( 'udrsp_daylight_sav', 1, 'AN' ),
                       array( 'udrsp_description', 30, 'AN' ),
                       array( 'udrsp_discard_in_units', 9, 'AN' ),
                       array( 'udrsp_discard_out_units', 9, 'AN' ),
                       array( 'udrsp_dst_city', 10, 'AN' ),
                       array( 'udrsp_dst_country', 4, 'AN' ),
                       array( 'udrsp_dst_country_numeric', 4, 'AN' ),
                       array( 'udrsp_dst_dialed', 32, 'AN' ),
                       array( 'udrsp_dst_imsi', 15, 'AN' ),
                       array( 'udrsp_dst_lrn', 10, 'AN' ),
                       array( 'udrsp_dst_mdn_msisd', 16, 'AN' ),
                       array( 'udrsp_dst_min', 10, 'AN' ),
                       array( 'udrsp_dst_state', 2, 'AN' ),
                       array( 'udrsp_duration', 8, 'AN' ),
                       array( 'udrsp_duration_ring', 8, 'AN' ),
                       array( 'udrsp_duration_connect', 8, 'AN' ),
                       array( 'udrsp_duration_unit', 2, 'AN' ),
                       array( 'udrsp_billed_market', 3, 'AN' ),
                       array( 'udrsp_market_npa', 6, 'AN' ),
                       array( 'udrsp_event', 3, 'AN' ),
                       array( 'udrsp_file_reference', 11, 'AN' ),
                       array( 'udrsp_ggsn_address_used', 20, 'AN' ),
                       array( 'udrsp_home_pmn', 5, 'AN' ),
                       array( 'udrsp_home_rmr_rerate', 1, 'AN' ),
                       array( 'udrsp_home_sid', 5, 'AN' ),
                       array( 'udrsp_interconnect', 2, 'AN' ),
                       array( 'udrsp_interconnect_amount', 11, 'AN' ),
                       array( 'udrsp_interconnect_flag', 1, 'AN' ),
                       array( 'udrsp_interconnect_toll', 1, 'AN' ),
                       array( 'udrsp_jip', 10, 'AN' ),
                       array( 'udrsp_ld_route', 10, 'AN' ),
                       array( 'udrsp_local_rec_seq_num', 9, 'AN' ),
                       array( 'udrsp_location_area_code', 3, 'AN' ),
                       array( 'udrsp_message_length', 7, 'AN' ),
                       array( 'udrsp_toll_mileage', 5, 'AN' ),
                       array( 'udrsp_ms_network_capability', 2, 'AN' ),
                       array( 'udrsp_network_initiation', 2, 'AN' ),
                       array( 'udrsp_no_air_flag', 1, 'AN' ),
                       array( 'udrsp_other_amount', 11, 'AN' ),
                       array( 'udrsp_other_local_tax', 11, 'AN' ),
                       array( 'udrsp_other_state_tax', 11, 'AN' ),
                       array( 'udrsp_pdp_type', 20, 'AN' ),
                       array( 'udrsp_pmn_test_active_ind', 1, 'AN' ),
                       array( 'udrsp_price', 11, 'AN' ),
                       array( 'udrsp_qos_neg_delay', 1, 'AN' ),
                       array( 'udrsp_qos_neg_mean_throughput', 2, 'AN' ),
                       array( 'udrsp_qos_neg_peak_throughput', 1, 'AN' ),
                       array( 'udrsp_qos_neg_precedence', 1, 'AN' ),
                       array( 'udrsp_qos_neg_reliability', 1, 'AN' ),
                       array( 'udrsp_qos_req_delay', 1, 'AN' ),
                       array( 'udrsp_qos_req_mean_throughput', 2, 'AN' ),
                       array( 'udrsp_qos_req_peak_throughput', 1, 'AN' ),
                       array( 'udrsp_qos_req_precedence', 1, 'AN' ),
                       array( 'udrsp_qos_req_reliability', 1, 'AN' ),
                       array( 'udrsp_rate_center', 5, 'AN' ),
                       array( 'udrsp_rec_extensions', 15, 'AN' ),
                       array( 'udrsp_rnc_unsent_downlink_vol', 10, 'AN' ),
                       array( 'udrsp_routing_area', 10, 'AN' ),
                       array( 'udrsp_served_market', 3, 'AN' ),
                       array( 'udrsp_served_pdp_addr', 20, 'AN' ),
                       array( 'udrsp_served_pmn', 5, 'AN' ),
                       array( 'udrsp_served_sid', 5, 'AN' ),
                       array( 'udrsp_service_feature', 2, 'AN' ),
                       array( 'udrsp_service_sub_feature', 4, 'AN' ),
                       array( 'udrsp_sgsn_address', 20, 'AN' ),
                       array( 'udrsp_sgsn_change', 1, 'AN' ),
                       array( 'udrsp_src_city', 10, 'AN' ),
                       array( 'udrsp_src_country', 4, 'AN' ),
                       array( 'udrsp_src_country_numeric', 4, 'AN' ),
                       array( 'udrsp_src_infra_hw_info', 20, 'AN' ),
                       array( 'udrsp_src_lrn', 10, 'AN' ),
                       array( 'udrsp_src_npa_nxx', 6, 'AN' ),
                       array( 'udrsp_src_state', 2, 'AN' ),
                       array( 'udrsp_surcharge_amount', 11, 'AN' ),
                       array( 'udrsp_surcharge_info', 2, 'AN' ),
                       array( 'udrsp_surcharge_misc_amount', 11, 'AN' ),
                       array( 'udrsp_surcharge_misc_info', 2, 'AN' ),
                       array( 'udrsp_switch_file_batch', 20, 'AN' ),
                       array( 'udrsp_switch_file', 10, 'AN' ),
                       array( 'udrsp_timezone_incr_done', 1, 'AN' ),
                       array( 'udrsp_tldn', 10, 'AN' ),
                       array( 'udrsp_toll_amount', 11, 'AN' ),
                       array( 'udrsp_toll_carrier', 5, 'AN' ),
                       array( 'udrsp_toll_local_tax', 11, 'AN' ),
                       array( 'udrsp_toll_multi_rate', 1, 'AN' ),
                       array( 'udrsp_toll_period', 2, 'AN' ),
                       array( 'udrsp_toll_spec_type', 2, 'AN' ),
                       array( 'udrsp_toll_state_tax', 11, 'AN' ),
                       array( 'udrsp_toll_tarif', 2, 'AN' ),
                       array( 'udrsp_toll_tax', 1, 'AN' ),
                       array( 'udrsp_toll_wide_area', 3, 'AN' ),
                       array( 'udrsp_trunk_group_incoming', 10, 'AN' ),
                       array( 'udrsp_trunk_group_outgoing', 10, 'AN' ),
                       array( 'udrsp_wnp_dip_charge_info', 4, 'AN' ),
                       array( 'udrsp_wnp_dip_charge_ind', 1, 'AN' ),
                       array( 'udrsp_world_timezone', 2, 'AN' ),
                       array( 'udrsp_zero_ld_chg_flag', 1, 'AN' ),
                       array( 'udrsp_org_coord_h', 5, 'AN' ),
                       array( 'udrsp_org_coord_v', 5, 'AN' ),
                       array( 'udrsp_gprs_gcdr_flag', 1, 'AN' ),
                       array( 'newline', 1, 'AN' ) );

##########################
#  UDR Version 1.0 Struct
#
$udrStruct = array( array( 'udr_partittion_key', 5, 'AN' ),
                    array( 'udr_phase_1_datetime', 12, 'AN' ),
                    array( 'udr_phase_1_result', 2, 'AN' ),
                    array( 'udr_phase_1_status', 1, 'AN' ),
                    array( 'udr_phase_2_datetime', 12, 'AN' ),
                    array( 'udr_phase_2_result', 2, 'AN' ),
                    array( 'udr_phase_2_status', 1, 'AN' ),
                    array( 'udr_phase_3_datetime', 12, 'AN' ),
                    array( 'udr_phase_3_result', 2, 'AN' ),
                    array( 'udr_phase_3_status', 1, 'AN' ),
                    array( 'udr_visibility_date', 12, 'AN' ),
                    array( 'udr_type_id', 5, 'AN' ),
                    array( 'udr_record_type_id', 1, 'AN' ),
                    array( 'udr_creator', 15, 'AN' ),
                    array( 'udr_creation_datetime', 12, 'AN' ),
                    array( 'udr_parent_id', 5, 'AN' ),
                    array( 'udr_access_area', 10, 'AN' ),
                    array( 'udr_account_authent', 10, 'AN' ),
                    array( 'udr_account_authent_type', 5, 'AN' ),
                    array( 'udr_account_id', 6, 'AN' ),
                    array( 'udr_air_amount', 11, 'AN' ),
                    array( 'udr_air_local_tax', 11, 'AN' ),
                    array( 'udr_air_state_tax', 11, 'AN' ),
                    array( 'udr_air_rate_period', 2, 'AN' ),
                    array( 'udr_apn_network_info', 20, 'AN' ),
                    array( 'udr_apn_operator_info', 20, 'AN' ),
                    array( 'udr_apn_selection_mode', 20, 'AN' ),
                    array( 'udr_billed_eid', 20, 'AN' ),
                    array( 'udr_billed_esn', 15, 'AN' ),
                    array( 'udr_billed_imei', 15, 'AN' ),
                    array( 'udr_billed_imsi', 15, 'AN' ),
                    array( 'udr_billed_mdn_msisd', 10, 'AN' ),
                    array( 'udr_billed_min', 10, 'AN' ),
                    array( 'udr_billed_celltower', 10, 'AN' ),
                    array( 'udr_call_end_reason', 2, 'AN' ),
                    array( 'udr_call_identifier_block', 10, 'AN' ),
                    array( 'udr_call_identifier_rcd_cnt', 5, 'AN' ),
                    array( 'udr_called_ton', 6, 'AN' ),
                    array( 'udr_caller_id', 10, 'AN' ),
                    array( 'udr_caller_id_block', 1, 'AN' ),
                    array( 'udr_cbc_prefix', 3, 'AN' ),
                    array( 'udr_change_condition', 1, 'AN' ),
                    array( 'udr_change_time', 12, 'AN' ),
                    array( 'udr_charge_in_packets', 10, 'AN' ),
                    array( 'udr_charge_in_units', 10, 'AN' ),
                    array( 'udr_charge_out_packets', 10, 'AN' ),
                    array( 'udr_charge_out_units', 10, 'AN' ),
                    array( 'udr_charge_packets', 10, 'AN' ),
                    array( 'udr_charging_characteristics', 1, 'AN' ),
                    array( 'udr_charging_info', 15, 'AN' ),
                    array( 'udr_ciber_type', 2, 'AN' ),
                    array( 'udr_conference_identifier', 1, 'AN' ),
                    array( 'udr_coord_h', 5, 'AN' ),
                    array( 'udr_coord_v', 5, 'AN' ),
                    array( 'udr_currency', 2, 'AN' ),
                    array( 'udr_datetime_end', 16, 'AN' ),
                    array( 'udr_datetime_connect', 16, 'AN' ),
                    array( 'udr_datetime_ring', 16, 'AN' ),
                    array( 'udr_datetime_start', 16, 'AN' ),
                    array( 'udr_daylight_sav', 1, 'AN' ),
                    array( 'udr_description', 30, 'AN' ),
                    array( 'udr_discard_in_units', 9, 'AN' ),
                    array( 'udr_discard_out_units', 9, 'AN' ),
                    array( 'udr_distribution_type_key', 1, 'AN' ),
                    array( 'udr_dst_prefix', 3, 'AN' ),
                    array( 'udr_dst_access_hw_info', 20, 'AN' ),
                    array( 'udr_dst_city', 10, 'AN' ),
                    array( 'udr_dst_country', 4, 'AN' ),
                    array( 'udr_dst_dialed', 32, 'AN' ),
                    array( 'udr_dst_info2', 21, 'AN' ),
                    array( 'udr_dst_imsi', 15, 'AN' ),
                    array( 'udr_dst_lrn', 10, 'AN' ),
                    array( 'udr_dst_mdn', 10, 'AN' ),
                    array( 'udr_dst_min', 10, 'AN' ),
                    array( 'udr_dst_state', 2, 'AN' ),
                    array( 'udr_dst_type', 2, 'AN' ),
                    array( 'udr_duration', 8, 'AN' ),
                    array( 'udr_duration_ring', 8, 'AN' ),
                    array( 'udr_duration_connect', 8, 'AN' ),
                    array( 'udr_duration_unit', 2, 'AN' ),
                    array( 'udr_dyn_addr_flag', 1, 'AN' ),
                    array( 'udr_billed_enode_id', 3, 'AN' ),
                    array( 'udr_market_npa_nxx', 6, 'AN' ),
                    array( 'udr_error_counter', 2, 'AN' ),
                    array( 'udr_event', 3, 'AN' ),
                    array( 'udr_file_reference', 11, 'AN' ),
                    array( 'udr_ggsn_address_used', 20, 'AN' ),
                    array( 'udr_home_pmn', 5, 'AN' ),
                    array( 'udr_home_rmr_rerate', 1, 'AN' ),
                    array( 'udr_home_sid', 5, 'AN' ),
                    array( 'udr_interconnect', 2, 'AN' ),
                    array( 'udr_interconnect_amount', 11, 'AN' ),
                    array( 'udr_interconnect_flag', 1, 'AN' ),
                    array( 'udr_interconnect_toll', 1, 'AN' ),
                    array( 'udr_jip', 10, 'AN' ),
                    array( 'udr_ld_route', 10, 'AN' ),
                    array( 'udr_local_rec_seq_num', 9, 'AN' ),
                    array( 'udr_message_length', 7, 'AN' ),
                    array( 'udr_toll_mileage', 5, 'AN' ),
                    array( 'udr_ms_network_capability', 2, 'AN' ),
                    array( 'udr_network_initiation', 2, 'AN' ),
                    array( 'udr_no_air_flag', 1, 'AN' ),
                    array( 'udr_oc_batch', 20, 'AN' ),
                    array( 'udr_oc_date', 12, 'AN' ),
                    array( 'udr_oc_user', 30, 'AN' ),
                    array( 'udr_other_amount', 11, 'AN' ),
                    array( 'udr_other_local_tax', 11, 'AN' ),
                    array( 'udr_other_state_tax', 11, 'AN' ),
                    array( 'udr_pdp_type', 20, 'AN' ),
                    array( 'udr_price', 11, 'AN' ),
                    array( 'udr_ps_header_id', 6, 'AN' ),
                    array( 'udr_qos_neg_delay', 1, 'AN' ),
                    array( 'udr_qos_neg_mean_throughput', 2, 'AN' ),
                    array( 'udr_qos_neg_peak_throughput', 1, 'AN' ),
                    array( 'udr_qos_neg_precedence', 1, 'AN' ),
                    array( 'udr_qos_neg_reliability', 1, 'AN' ),
                    array( 'udr_qos_req_delay', 1, 'AN' ),
                    array( 'udr_qos_req_mean_throughput', 2, 'AN' ),
                    array( 'udr_qos_req_peak_throughput', 1, 'AN' ),
                    array( 'udr_qos_req_precedence', 1, 'AN' ),
                    array( 'udr_qos_req_reliability', 1, 'AN' ),
                    array( 'udr_rate_center', 5, 'AN' ),
                    array( 'udr_rec_extensions', 15, 'AN' ),
                    array( 'udr_rnc_unsent_downlink_vol', 10, 'AN' ),
                    array( 'udr_served_market', 3, 'AN' ),
                    array( 'udr_served_pmn', 5, 'AN' ),
                    array( 'udr_served_sid', 5, 'AN' ),
                    array( 'udr_served_pdp_addr', 20, 'AN' ),
                    array( 'udr_service_feature', 2, 'AN' ),
                    array( 'udr_service_sub_feature', 4, 'AN' ),
                    array( 'udr_sgsn_address', 20, 'AN' ),
                    array( 'udr_sgsn_change', 1, 'AN' ),
                    array( 'udr_shell_id', 6, 'AN' ),
                    array( 'udr_shell_login_info', 6, 'AN' ),
                    array( 'udr_src_access_hw_info', 20, 'AN' ),
                    array( 'udr_src_city', 10, 'AN' ),
                    array( 'udr_src_country', 4, 'AN' ),
                    array( 'udr_src_info1', 10, 'AN' ),
                    array( 'udr_src_infra_hw_info', 20, 'AN' ),
                    array( 'udr_src_lrn', 10, 'AN' ),
                    array( 'udr_src_npa_nxx', 6, 'AN' ),
                    array( 'udr_src_state', 2, 'AN' ),
                    array( 'udr_surcharge_amount', 11, 'AN' ),
                    array( 'udr_surcharge_info', 2, 'AN' ),
                    array( 'udr_surcharge_misc_amount', 11, 'AN' ),
                    array( 'udr_surcharge_misc_info', 2, 'AN' ),
                    array( 'udr_switch_file_batch', 20, 'AN' ),
                    array( 'udr_switch_file', 10, 'AN' ),
                    array( 'udr_system_type', 1, 'AN' ),
                    array( 'udr_ticket_number', 10, 'AN' ),
                    array( 'udr_ticket_number_child', 10, 'AN' ),
                    array( 'udr_ticket_total_children', 10, 'AN' ),
                    array( 'udr_timezone_incr_done', 1, 'AN' ),
                    array( 'udr_tldn', 10, 'AN' ),
                    array( 'udr_toll_amount', 11, 'AN' ),
                    array( 'udr_toll_carrier', 5, 'AN' ),
                    array( 'udr_toll_local_tax', 11, 'AN' ),
                    array( 'udr_toll_multi_rate', 1, 'AN' ),
                    array( 'udr_toll_period', 2, 'AN' ),
                    array( 'udr_toll_spec_type', 2, 'AN' ),
                    array( 'udr_toll_state_tax', 11, 'AN' ),
                    array( 'udr_toll_tarif', 2, 'AN' ),
                    array( 'udr_toll_tax', 1, 'AN' ),
                    array( 'udr_toll_wide_area', 3, 'AN' ),
                    array( 'udr_trunk_group_incoming', 10, 'AN' ),
                    array( 'udr_trunk_group_outgoing', 10, 'AN' ),
                    array( 'udr_wnp_dip_charge_info', 4, 'AN' ),
                    array( 'udr_wnp_dip_charge_ind', 1, 'AN' ),
                    array( 'udr_world_timezone', 2, 'AN' ),
                    array( 'udr_zero_ld_chg_flag', 1, 'AN' ),
                    array( 'udr_billing_group_id', 16, 'AN' ),
                    array( 'udr_billing_period', 6, 'AN' ),
                    array( 'udr_rerate_seq_id', 16, 'AN' ),
                    array( 'udr_served_enode_key', 3, 'AN' ),
                    array( 'newline', 1, 'AN' ) );


#########################
#  UDR Version 2.0 Struct
#
$udr2Struct = array( array( 'udr_partittion_key', 8, 'AN' ),
                     array( 'udr_phase_0_datetime', 14, 'AN' ),
                     array( 'udr_phase_0_result', 16, 'AN' ),
                     array( 'udr_phase_0_status', 2, 'AN' ),
                     array( 'udr_phase_1_datetime', 12, 'AN' ),
                     array( 'udr_phase_1_result', 2, 'AN' ),
                     array( 'udr_phase_1_status', 1, 'AN' ),
                     array( 'udr_phase_2_datetime', 12, 'AN' ),
                     array( 'udr_phase_2_result', 2, 'AN' ),
                     array( 'udr_phase_2_status', 1, 'AN' ),
                     array( 'udr_phase_3_datetime', 12, 'AN' ),
                     array( 'udr_phase_3_result', 2, 'AN' ),
                     array( 'udr_phase_3_status', 2, 'AN' ),
                     array( 'udr_visibility_date', 12, 'AN' ),
                     array( 'udr_type_id', 5, 'AN' ),
                     array( 'udr_record_type_id', 1, 'AN' ),
                     array( 'udr_creator', 15, 'AN' ),
                     array( 'udr_creation_datetime', 12, 'AN' ),
                     array( 'udr_parent_id', 5, 'AN' ),
                     array( 'udr_access_area', 10, 'AN' ),
                     array( 'udr_account_authent', 10, 'AN' ),
                     array( 'udr_account_authent_type', 5, 'AN' ),
                     array( 'udr_account_id', 6, 'AN' ),
                     array( 'udr_air_amount', 11, 'AN' ),
                     array( 'udr_air_local_tax', 11, 'AN' ),
                     array( 'udr_air_rate_period', 2, 'AN' ),
                     array( 'udr_air_state_tax', 11, 'AN' ),
                     array( 'udr_apn_network_info', 20, 'AN' ),
                     array( 'udr_apn_operator_info', 20, 'AN' ),
                     array( 'udr_apn_selection_mode', 20, 'AN' ),
                     array( 'udr_billed_celltower', 10, 'AN' ),
                     array( 'udr_billed_eid', 20, 'AN' ),
                     array( 'udr_billed_esn', 15, 'AN' ),
                     array( 'udr_billed_imei', 15, 'AN' ),
                     array( 'udr_billed_imsi', 15, 'AN' ),
                     array( 'udr_billed_mdn_msisd', 16, 'AN' ),
                     array( 'udr_billed_esn_meid', 20, 'AN' ),
                     array( 'udr_billed_meid_chk_digit', 2, 'AN' ),
                     array( 'udr_billed_min', 10, 'AN' ),
                     array( 'udr_billed_sim', 20, 'AN' ),
                     array( 'udr_billing_group_id', 16, 'AN' ),
                     array( 'udr_billing_period', 6, 'AN' ),
                     array( 'udr_billman_chrg_subtype_key', 6, 'AN' ),
                     array( 'udr_call_end_reason', 2, 'AN' ),
                     array( 'udr_call_identifier_block', 10, 'AN' ),
                     array( 'udr_call_identifier_rcd_cnt', 5, 'AN' ),
                     array( 'udr_called_ton', 6, 'AN' ),
                     array( 'udr_caller_id', 16, 'AN' ),
                     array( 'udr_caller_id_block', 1, 'AN' ),
                     array( 'udr_camel_service_key', 10, 'AN' ),
                     array( 'udr_camel_service_level', 1, 'AN' ),
                     array( 'udr_camel_dest_number', 32, 'AN' ),
                     array( 'udr_camel_invocation_fee', 11, 'AN' ),
                     array( 'udr_camel_tax', 11, 'AN' ),
                     array( 'udr_cbc_prefix', 3, 'AN' ),
                     array( 'udr_change_condition', 1, 'AN' ),
                     array( 'udr_change_time', 12, 'AN' ),
                     array( 'udr_charge_in_packets', 10, 'AN' ),
                     array( 'udr_charge_in_units', 10, 'AN' ),
                     array( 'udr_charge_out_packets', 10, 'AN' ),
                     array( 'udr_charge_out_units', 10, 'AN' ),
                     array( 'udr_charge_packets', 10, 'AN' ),
                     array( 'udr_charging_characteristics', 1, 'AN' ),
                     array( 'udr_charging_info', 15, 'AN' ),
                     array( 'udr_ciber_type', 2, 'AN' ),
                     array( 'udr_conference_identifier', 1, 'AN' ),
                     array( 'udr_coord_h', 5, 'AN' ),
                     array( 'udr_coord_v', 5, 'AN' ),
                     array( 'udr_currency', 2, 'AN' ),
                     array( 'udr_datetime_end', 16, 'AN' ),
                     array( 'udr_datetime_connect', 16, 'AN' ),
                     array( 'udr_datetime_ring', 16, 'AN' ),
                     array( 'udr_datetime_start', 16, 'AN' ),
                     array( 'udr_daylight_sav', 1, 'AN' ),
                     array( 'udr_description', 30, 'AN' ),
                     array( 'udr_discard_in_units', 9, 'AN' ),
                     array( 'udr_discard_out_units', 9, 'AN' ),
                     array( 'udr_distribution_type_key', 16, 'AN' ),
                     array( 'udr_dst_access_hw_info', 20, 'AN' ),
                     array( 'udr_dst_city', 10, 'AN' ),
                     array( 'udr_dst_country', 4, 'AN' ),
                     array( 'udr_dst_country_numeric', 4, 'AN' ),
                     array( 'udr_dst_dialed', 32, 'AN' ),
                     array( 'udr_dst_info2', 21, 'AN' ),
                     array( 'udr_dst_imsi', 15, 'AN' ),
                     array( 'udr_dst_lrn', 10, 'AN' ),
                     array( 'udr_dst_mdn_msisd', 16, 'AN' ),
                     array( 'udr_dst_min', 10, 'AN' ),
                     array( 'udr_dst_prefix', 10, 'AN' ),
                     array( 'udr_dst_state', 2, 'AN' ),
                     array( 'udr_dst_type', 2, 'AN' ),
                     array( 'udr_duration', 8, 'AN' ),
                     array( 'udr_duration_connect', 8, 'AN' ),
                     array( 'udr_duration_ring', 8, 'AN' ),
                     array( 'udr_duration_unit', 2, 'AN' ),
                     array( 'udr_dyn_addr_flag', 1, 'AN' ),
                     array( 'udr_billed_market', 3, 'AN' ),
                     array( 'udr_market_npa_nxx', 6, 'AN' ),
                     array( 'udr_error_counter', 2, 'AN' ),
                     array( 'udr_event', 3, 'AN' ),
                     array( 'udr_file_reference', 11, 'AN' ),
                     array( 'udr_ggsn_address_used', 20, 'AN' ),
                     array( 'udr_home_pmn', 5, 'AN' ),
                     array( 'udr_home_rmr_rerate', 1, 'AN' ),
                     array( 'udr_home_sid', 5, 'AN' ),
                     array( 'udr_interconnect', 2, 'AN' ),
                     array( 'udr_interconnect_amount', 11, 'AN' ),
                     array( 'udr_interconnect_flag', 1, 'AN' ),
                     array( 'udr_interconnect_toll', 1, 'AN' ),
                     array( 'udr_jip', 10, 'AN' ),
                     array( 'udr_ld_route', 10, 'AN' ),
                     array( 'udr_local_rec_seq_num', 9, 'AN' ),
                     array( 'udr_location_area_code', 3, 'AN' ),
                     array( 'udr_message_length', 7, 'AN' ),
                     array( 'udr_toll_mileage', 5, 'AN' ),
                     array( 'udr_ms_network_capability', 2, 'AN' ),
                     array( 'udr_network_initiation', 2, 'AN' ),
                     array( 'udr_no_air_flag', 1, 'AN' ),
                     array( 'udr_oc_batch', 16, 'AN' ),
                     array( 'udr_oc_date', 16, 'AN' ),
                     array( 'udr_oc_test_batch', 16, 'AN' ),
                     array( 'udr_oc_user', 30, 'AN' ),
                     array( 'udr_other_amount', 11, 'AN' ),
                     array( 'udr_other_local_tax', 11, 'AN' ),
                     array( 'udr_other_state_tax', 11, 'AN' ),
                     array( 'udr_pdp_type', 20, 'AN' ),
                     array( 'udr_pmn_test_active_ind', 1, 'AN' ),
                     array( 'udr_price', 11, 'AN' ),
                     array( 'udr_ps_header_id', 6, 'AN' ),
                     array( 'udr_qos_neg_delay', 1, 'AN' ),
                     array( 'udr_qos_neg_mean_throughput', 2, 'AN' ),
                     array( 'udr_qos_neg_peak_throughput', 1, 'AN' ),
                     array( 'udr_qos_neg_precedence', 1, 'AN' ),
                     array( 'udr_qos_neg_reliability', 1, 'AN' ),
                     array( 'udr_qos_req_delay', 1, 'AN' ),
                     array( 'udr_qos_req_mean_throughput', 2, 'AN' ),
                     array( 'udr_qos_req_peak_throughput', 1, 'AN' ),
                     array( 'udr_qos_req_precedence', 1, 'AN' ),
                     array( 'udr_qos_req_reliability', 1, 'AN' ),
                     array( 'udr_rate_center', 5, 'AN' ),
                     array( 'udr_rec_extensions', 15, 'AN' ),
                     array( 'udr_rerate_seq_id', 16, 'AN' ),
                     array( 'udr_rnc_unsent_downlink_vol', 10, 'AN' ),
                     array( 'udr_routing_area', 10, 'AN' ),
                     array( 'udr_rpa_run_id', 10, 'AN' ),
                     array( 'udr_served_market', 3, 'AN' ),
                     array( 'udr_served_pdp_addr', 20, 'AN' ),
                     array( 'udr_served_pmn', 5, 'AN' ),
                     array( 'udr_served_sid', 5, 'AN' ),
                     array( 'udr_service_feature', 2, 'AN' ),
                     array( 'udr_service_sub_feature', 4, 'AN' ),
                     array( 'udr_sgsn_address', 20, 'AN' ),
                     array( 'udr_sgsn_change', 1, 'AN' ),
                     array( 'udr_shell_id', 6, 'AN' ),
                     array( 'udr_shell_login_info', 6, 'AN' ),
                     array( 'udr_src_access_hw_info', 20, 'AN' ),
                     array( 'udr_src_city', 10, 'AN' ),
                     array( 'udr_src_country', 4, 'AN' ),
                     array( 'udr_src_country_numeric', 4, 'AN' ),
                     array( 'udr_src_info1', 10, 'AN' ),
                     array( 'udr_src_infra_hw_info', 20, 'AN' ),
                     array( 'udr_src_lrn', 10, 'AN' ),
                     array( 'udr_src_npa_nxx', 6, 'AN' ),
                     array( 'udr_src_state', 2, 'AN' ),
                     array( 'udr_surcharge_amount', 11, 'AN' ),
                     array( 'udr_surcharge_info', 2, 'AN' ),
                     array( 'udr_surcharge_misc_amount', 11, 'AN' ),
                     array( 'udr_surcharge_misc_info', 2, 'AN' ),
                     array( 'udr_switch_file_batch', 20, 'AN' ),
                     array( 'udr_switch_file', 10, 'AN' ),
                     array( 'udr_system_type', 1, 'AN' ),
                     array( 'udr_ticket_number', 10, 'AN' ),
                     array( 'udr_ticket_number_child', 10, 'AN' ),
                     array( 'udr_ticket_total_children', 10, 'AN' ),
                     array( 'udr_timezone_incr_done', 1, 'AN' ),
                     array( 'udr_tldn', 10, 'AN' ),
                     array( 'udr_toll_amount', 11, 'AN' ),
                     array( 'udr_toll_carrier', 5, 'AN' ),
                     array( 'udr_toll_local_tax', 11, 'AN' ),
                     array( 'udr_toll_multi_rate', 1, 'AN' ),
                     array( 'udr_toll_period', 2, 'AN' ),
                     array( 'udr_toll_spec_type', 2, 'AN' ),
                     array( 'udr_toll_state_tax', 11, 'AN' ),
                     array( 'udr_toll_tarif', 2, 'AN' ),
                     array( 'udr_toll_tax', 1, 'AN' ),
                     array( 'udr_toll_wide_area', 3, 'AN' ),
                     array( 'udr_trunk_group_incoming', 10, 'AN' ),
                     array( 'udr_trunk_group_outgoing', 10, 'AN' ),
                     array( 'udr_reset_binary_code', 10, 'AN' ),
                     array( 'udr_wnp_dip_charge_info', 4, 'AN' ),
                     array( 'udr_wnp_dip_charge_ind', 1, 'AN' ),
                     array( 'udr_world_timezone', 2, 'AN' ),
                     array( 'udr_zero_ld_chg_flag', 1, 'AN' ),
                     array( 'udr_org_coord_h', 5, 'AN' ),
                     array( 'udr_org_coord_v', 5, 'AN' ),
                     array( 'udr_gprs_gcdr_flag', 1, 'AN' ),
                     array( 'newline', 1, 'AN' ) );


############################
#  UDRCH Structure
$udrchStruct = array( array( 'udrch_partittion_key', 8, 'AN' ),
                      array( 'udrch_phase_1_datetime', 12, 'AN' ),
                      array( 'udrch_phase_1_result', 2, 'AN' ),
                      array( 'udrch_phase_1_status', 1, 'AN' ),
                      array( 'udrch_phase_2_datetime', 12, 'AN' ),
                      array( 'udrch_phase_2_result', 2, 'AN' ),
                      array( 'udrch_phase_2_status', 1, 'AN' ),
                      array( 'udrch_phase_3_datetime', 12, 'AN' ),
                      array( 'udrch_phase_3_result', 2, 'AN' ),
                      array( 'udrch_phase_3_status', 1, 'AN' ),
                      array( 'udrch_visibility_date', 12, 'AN' ),
                      array( 'udrch_type_id', 5, 'AN' ),
                      array( 'udrch_record_type_id', 1, 'AN' ),
                      array( 'udrch_creator', 15, 'AN' ),
                      array( 'udrch_creation_datetime', 12, 'AN' ),
                      array( 'udrch_parent_id', 5, 'AN' ),
                      array( 'udrch_access_area', 10, 'AN' ),
                      array( 'udrch_account_authent', 10, 'AN' ),
                      array( 'udrch_account_authent_type', 5, 'AN' ),
                      array( 'udrch_account_id', 6, 'AN' ),
                      array( 'udrch_air_amount', 11, 'AN' ),
                      array( 'udrch_air_local_tax', 11, 'AN' ),
                      array( 'udrch_air_state_tax', 11, 'AN' ),
                      array( 'udrch_air_rate_period', 2, 'AN' ),
                      array( 'udrch_apn_network_info', 20, 'AN' ),
                      array( 'udrch_apn_operator_info', 20, 'AN' ),
                      array( 'udrch_apn_selection_mode', 20, 'AN' ),
                      array( 'udrch_billed_eid', 20, 'AN' ),
                      array( 'udrch_billed_esn', 15, 'AN' ),
                      array( 'udrch_billed_imei', 15, 'AN' ),
                      array( 'udrch_billed_imsi', 15, 'AN' ),
                      array( 'udrch_billed_mdn_msisd', 10, 'AN' ),
                      array( 'udrch_billed_min', 10, 'AN' ),
                      array( 'udrch_billed_celltower', 10, 'AN' ),
                      array( 'udrch_call_end_reason', 2, 'AN' ),
                      array( 'udrch_call_identifier_block', 10, 'AN' ),
                      array( 'udrch_call_identifier_rcd_cnt', 5, 'AN' ),
                      array( 'udrch_called_ton', 6, 'AN' ),
                      array( 'udrch_caller_id', 10, 'AN' ),
                      array( 'udrch_caller_id_block', 1, 'AN' ),
                      array( 'udrch_cbc_prefix', 3, 'AN' ),
                      array( 'udrch_change_condition', 1, 'AN' ),
                      array( 'udrch_change_time', 12, 'AN' ),
                      array( 'udrch_charge_in_packets', 10, 'AN' ),
                      array( 'udrch_charge_in_units', 10, 'AN' ),
                      array( 'udrch_charge_out_packets', 10, 'AN' ),
                      array( 'udrch_charge_out_units', 10, 'AN' ),
                      array( 'udrch_charge_packets', 10, 'AN' ),
                      array( 'udrch_charging_characteristics', 1, 'AN' ),
                      array( 'udrch_charging_info', 15, 'AN' ),
                      array( 'udrch_ciber_type', 2, 'AN' ),
                      array( 'udrch_conference_identifier', 1, 'AN' ),
                      array( 'udrch_coord_h', 5, 'AN' ),
                      array( 'udrch_coord_v', 5, 'AN' ),
                      array( 'udrch_currency', 2, 'AN' ),
                      array( 'udrch_datetime_end', 16, 'AN' ),
                      array( 'udrch_datetime_connect', 16, 'AN' ),
                      array( 'udrch_datetime_evento', 16, 'AN' ),
                      array( 'udrch_datetime_start', 16, 'AN' ),
                      array( 'udrch_daylight_sav', 1, 'AN' ),
                      array( 'udrch_description', 30, 'AN' ),
                      array( 'udrch_discard_in_units', 9, 'AN' ),
                      array( 'udrch_discard_out_units', 9, 'AN' ),
                      array( 'udrch_distribution_type_key', 16, 'AN' ),
                      array( 'udrch_dst_prefix', 3, 'AN' ),
                      array( 'udrch_dst_access_hw_info', 20, 'AN' ),
                      array( 'udrch_dst_city', 10, 'AN' ),
                      array( 'udrch_dst_country', 4, 'AN' ),
                      array( 'udrch_dst_dialed', 32, 'AN' ),
                      array( 'udrch_dst_info2', 21, 'AN' ),
                      array( 'udrch_dst_imsi', 15, 'AN' ),
                      array( 'udrch_dst_lrn', 10, 'AN' ),
                      array( 'udrch_dst_mdn', 10, 'AN' ),
                      array( 'udrch_dst_min', 10, 'AN' ),
                      array( 'udrch_dst_state', 2, 'AN' ),
                      array( 'udrch_dst_type', 2, 'AN' ),
                      array( 'udrch_duration', 6, 'AN' ),
                      array( 'udrch_duration_ring', 6, 'AN' ),
                      array( 'udrch_duration_connect', 6, 'AN' ),
                      array( 'udrch_duration_unit', 2, 'AN' ),
                      array( 'udrch_dyn_addr_flag', 1, 'AN' ),
                      array( 'udrch_billed_enode_id', 3, 'AN' ),
                      array( 'udrch_market_npa_nxx', 6, 'AN' ),
                      array( 'udrch_error_counter', 2, 'AN' ),
                      array( 'udrch_event', 3, 'AN' ),
                      array( 'udrch_file_reference', 11, 'AN' ),
                      array( 'udrch_ggsn_address_used', 20, 'AN' ),
                      array( 'udrch_home_pmn', 5, 'AN' ),
                      array( 'udrch_home_rmr_rerate', 1, 'AN' ),
                      array( 'udrch_home_sid', 5, 'AN' ),
                      array( 'udrch_interconnect', 2, 'AN' ),
                      array( 'udrch_interconnect_amount', 11, 'AN' ),
                      array( 'udrch_interconnect_flag', 1, 'AN' ),
                      array( 'udrch_interconnect_toll', 1, 'AN' ),
                      array( 'udrch_jip', 10, 'AN' ),
                      array( 'udrch_ld_route', 10, 'AN' ),
                      array( 'udrch_local_rec_seq_num', 9, 'AN' ),
                      array( 'udrch_message_length', 7, 'AN' ),
                      array( 'udrch_toll_mileage', 5, 'AN' ),
                      array( 'udrch_ms_network_capability', 2, 'AN' ),
                      array( 'udrch_network_initiation', 2, 'AN' ),
                      array( 'udrch_no_air_flag', 1, 'AN' ),
                      array( 'udrch_oc_batch', 20, 'AN' ),
                      array( 'udrch_oc_date', 16, 'AN' ),
                      array( 'udrch_oc_user', 30, 'AN' ),
                      array( 'udrch_other_amount', 11, 'AN' ),
                      array( 'udrch_other_local_tax', 11, 'AN' ),
                      array( 'udrch_other_state_tax', 11, 'AN' ),
                      array( 'udrch_pdp_type', 20, 'AN' ),
                      array( 'udrch_price', 11, 'AN' ),
                      array( 'udrch_ps_header_id', 6, 'AN' ),
                      array( 'udrch_qos_neg_delay', 1, 'AN' ),
                      array( 'udrch_qos_neg_mean_throughput', 2, 'AN' ),
                      array( 'udrch_qos_neg_peak_throughput', 1, 'AN' ),
                      array( 'udrch_qos_neg_precedence', 1, 'AN' ),
                      array( 'udrch_qos_neg_reliability', 1, 'AN' ),
                      array( 'udrch_qos_req_delay', 1, 'AN' ),
                      array( 'udrch_qos_req_mean_throughput', 2, 'AN' ),
                      array( 'udrch_qos_req_peak_throughput', 1, 'AN' ),
                      array( 'udrch_qos_req_precedence', 1, 'AN' ),
                      array( 'udrch_qos_req_reliability', 1, 'AN' ),
                      array( 'udrch_rate_center', 5, 'AN' ),
                      array( 'udrch_rec_extensions', 15, 'AN' ),
                      array( 'udrch_rnc_unsent_downlink_vol', 10, 'AN' ),
                      array( 'udrch_served_market', 3, 'AN' ),
                      array( 'udrch_served_pmn', 5, 'AN' ),
                      array( 'udrch_served_sid', 5, 'AN' ),
                      array( 'udrch_served_pdp_addr', 20, 'AN' ),
                      array( 'udrch_service_feature', 2, 'AN' ),
                      array( 'udrch_service_sub_feature', 4, 'AN' ),
                      array( 'udrch_sgsn_address', 20, 'AN' ),
                      array( 'udrch_sgsn_change', 1, 'AN' ),
                      array( 'udrch_shell_id', 6, 'AN' ),
                      array( 'udrch_shell_login_info', 6, 'AN' ),
                      array( 'udrch_src_access_hw_info', 20, 'AN' ),
                      array( 'udrch_src_city', 10, 'AN' ),
                      array( 'udrch_src_country', 4, 'AN' ),
                      array( 'udrch_src_info1', 10, 'AN' ),
                      array( 'udrch_src_infra_hw_info', 20, 'AN' ),
                      array( 'udrch_src_lrn', 10, 'AN' ),
                      array( 'udrch_src_npa_nxx', 6, 'AN' ),
                      array( 'udrch_src_state', 2, 'AN' ),
                      array( 'udrch_surcharge_amount', 11, 'AN' ),
                      array( 'udrch_surcharge_info', 2, 'AN' ),
                      array( 'udrch_surcharge_misc_amount', 11, 'AN' ),
                      array( 'udrch_surcharge_misc_info', 2, 'AN' ),
                      array( 'udrch_switch_file_batch', 20, 'AN' ),
                      array( 'udrch_switch_file', 10, 'AN' ),
                      array( 'udrch_system_type', 1, 'AN' ),
                      array( 'udrch_ticket_number', 10, 'AN' ),
                      array( 'udrch_ticket_number_child', 10, 'AN' ),
                      array( 'udrch_ticket_total_children', 10, 'AN' ),
                      array( 'udrch_timezone_incr_done', 1, 'AN' ),
                      array( 'udrch_tldn', 10, 'AN' ),
                      array( 'udrch_toll_amount', 11, 'AN' ),
                      array( 'udrch_toll_carrier', 1, 'AN' ),
                      array( 'udrch_toll_local_tax', 11, 'AN' ),
                      array( 'udrch_toll_multi_rate', 1, 'AN' ),
                      array( 'udrch_toll_period', 2, 'AN' ),
                      array( 'udrch_toll_spec_type', 2, 'AN' ),
                      array( 'udrch_toll_state_tax', 11, 'AN' ),
                      array( 'udrch_toll_tarif', 2, 'AN' ),
                      array( 'udrch_toll_tax', 1, 'AN' ),
                      array( 'udrch_toll_wide_area', 3, 'AN' ),
                      array( 'udrch_trunk_group_incoming', 10, 'AN' ),
                      array( 'udrch_trunk_group_outgoing', 10, 'AN' ),
                      array( 'udrch_wnp_dip_charge_info', 4, 'AN' ),
                      array( 'udrch_wnp_dip_charge_ind', 1, 'AN' ),
                      array( 'udrch_world_timezone', 2, 'AN' ),
                      array( 'udrch_zero_ld_chg_flag', 1, 'AN' ),
                      array( 'udrch_billing_group_id', 16, 'AN' ),
                      array( 'udrch_billing_period', 6, 'AN' ),
                      array( 'udrch_rerate_seq_id', 16, 'AN' ),
                      array( 'udrch_served_enode', 16, 'AN' ),
                      array( 'newline', 1, 'AN' ) );


#################
#  CALCDR Record
#
$calCDRStruct = array( array( 'CALCDR-SRVCLASS', 1, 'AN' ),
                       array( 'CALCDR-BILLED-MDN', 5, 'BI' ),
                       array( 'CALCDR-BILLED-MDN-FIL', 2, 'BI' ),
                       array( 'CALCDR-CALL-DATE-YY', 1, 'BI' ),
                       array( 'CALCDR-CALL-DATE-MM', 1, 'BI' ),
                       array( 'CALCDR-CALL-DATE-DD', 1, 'BI' ),
                       array( 'CALCDR-CALL-HH', 1, 'BI' ),
                       array( 'CALCDR-CALL-MM', 1, 'BI' ),
                       array( 'CALCDR-CALL-SS', 1, 'BI' ),
                       array( 'CALCDR-BILLED-ESN-IMEI', 7, 'BI' ),
                       array( 'CALCDR-BILLED-MIN-IMSI', 5, 'BI' ),
                       array( 'CALCDR-BILLED-MIN-FIL', 2, 'BI' ),
                       array( 'CALCDR-BILLED-EID', 6, 'BI' ),
                       array( 'CALCDR-PASS-AMT', 5, 'SP' ),
                       array( 'CALCDR-BILLED-IND', 2, 'UN' ),
                       array( 'CALCDR-SERV-MARKET', 3, 'UN' ),
                       array( 'CALCDR-DIALED-MDN', 18, 'AN' ),
                       array( 'CALCDR-DES-CTRY', 5, 'AN' ),
                       array( 'CALCDR-DES-MDN', 5, 'BI' ),
                       array( 'CALCDR-DES-MDN-FIL', 2, 'BI' ),
                       array( 'CALCDR-DES-MIN', 5, 'BI' ),
                       array( 'CALCDR-MIN-FIL', 2, 'BI' ),
                       array( 'CALCDR-QUE-IND', 2, 'UN' ),
                       array( 'CALCDR-DIRECTION', 2, 'AN' ),
                       array( 'CALCDR-SPECIAL-FEATURE', 2, 'UN' ),
                       array( 'CALCDR-CHARGE-MM', 2, 'BI' ),
                       array( 'CALCDR-CHARGE-SS', 1, 'BI' ),
                       array( 'CALCDR-ERROR-RETRY', 2, 'UN' ),
                       array( 'CALCDR-ZERO-LD', 1, 'AN' ),
                       array( 'CALCDR-NO-AIR', 1, 'AN' ),
                       array( 'CALCDR-XNUMBER', 5, 'BI' ),
                       array( 'CALCDR-CELL', 7, 'AN' ),
                       array( 'CALCDR-CELL-FIL', 3, 'AN' ),
                       array( 'CALCDR-CALL-DATE-YY-R', 1, 'BI' ),
                       array( 'CALCDR-CALL-DATE-MM-R', 1, 'BI' ),
                       array( 'CALCDR-CALL-DATE-DD-R', 1, 'BI' ),
                       array( 'CALCDR-CALL-HH-R', 1, 'BI' ),
                       array( 'CALCDR-CALL-MM-R', 1, 'BI' ),
                       array( 'CALCDR-CALL-SS-R', 1, 'BI' ),
                       array( 'CALCDR-CHARGE-MM-R', 2, 'BI' ),
                       array( 'CALCDR-CHARGE-SS-R', 1, 'BI' ),
                       array( 'CALCDR-CHARGE-UNITS', 3, 'BI' ),
                       array( 'CALCDR-DISCARD-UNITS', 3, 'BI' ),
                       array( 'CALCDR-SERV-SITE-ID', 2, 'BI' ),
                       array( 'CALCDR-BILLED-SITE-ID', 2, 'BI' ),
                       array( 'CALCDR-LD-ROUTE', 7, 'AN' ),
                       array( 'CALCDR-LD-ROUTE-FIL', 3, 'AN' ),
                       array( 'CALCDR-ERROR-CODE', 1, 'AN' ),
                       array( 'CALCDR-ORG-IND', 1, 'AN' ),
                       array( 'CALCDR-TOL-CARRIER', 2, 'BI' ),
                       array( 'CALCDR-INCOMPLETE', 1, 'AN' ),
                       array( 'CALCDR-BILLED-IND-OVERRIDE', 1, 'AN' ),
                       array( 'CALCDR-SPLIT-BILL-IND', 1, 'AN' ),
                       array( 'CALCDR-CALLER-ID', 5, 'BI' ),
                       array( 'CALCDR-CALLERID-PRESENT', 1, 'AN' ),
                       array( 'CALCDR-CALLER-ID-FIL', 1, 'BI' ),
                       array( 'CALCDR-TMZN-INCR-DONE', 1, 'AN' ),
                       array( 'CALCDR-BYP-CRS-MKT-RMG', 1, 'AN' ),
                       array( 'CALCDR-SWITCH-FILE', 10, 'AN' ),
                       array( 'CALCDR-LRN', 5, 'BI' ),
                       array( 'CALCDR-TLDN', 5, 'BI' ),
                       array( 'CALCDR-WNP-DIP-CHARGE-IND', 1, 'AN' ),
                       array( 'CALCDR-TRAIL-FIL', 25, 'AN' ) );


#####################
#  CALCDR v2.0 Record
#
$calCDR2Struct = array( array( 'CALCDR2-SRVCLASS', 1, 'AN' ),
                        array( 'CALCDR2-BILLED-MDN-MSISDN', 5, 'BI' ),
                        array( 'CALCDR2-BILLED-MDN-FIL', 2, 'BI' ),
                        array( 'CALCDR2-CALL-DATE-YY', 1, 'BI' ),
                        array( 'CALCDR2-CALL-DATE-MM', 1, 'BI' ),
                        array( 'CALCDR2-CALL-DATE-DD', 1, 'BI' ),
                        array( 'CALCDR2-CALL-HH', 1, 'BI' ),
                        array( 'CALCDR2-CALL-MM', 1, 'BI' ),
                        array( 'CALCDR2-CALL-SS', 1, 'BI' ),
                        array( 'CALCDR2-BILLED-ESN-IMEI', 7, 'BI' ),
                        array( 'CALCDR2-BILLED-MIN-IMSI', 5, 'BI' ),
                        array( 'CALCDR2-BILLED-MIN-FIL', 2, 'BI' ),
                        array( 'CALCDR2-BILLED-EID', 6, 'BI' ),
                        array( 'CALCDR2-PASS-AMT', 5, 'SP' ),
                        array( 'CALCDR2-BILLED-IND', 2, 'UN' ),
                        array( 'CALCDR2-SERV-MARKET', 3, 'UN' ),
                        array( 'CALCDR2-DIALED-MDN', 18, 'AN' ),
                        array( 'CALCDR2-DES-CTRY-1', 3, 'AN' ),
                        array( 'CALCDR2-DES-CTRY-2', 2, 'AN' ),
                        array( 'CALCDR2-DES-MDN-15', 5, 'BI' ),
                        array( 'CALCDR2-DES-MDN-FIL', 2, 'BI' ),
                        array( 'CALCDR2-DES-MIN-IMSI', 5, 'BI' ),
                        array( 'CALCDR2-MIN-FIL', 2, 'BI' ),
                        array( 'CALCDR2-QUE-IND', 2, 'UN' ),
                        array( 'CALCDR2-DIRECTION', 2, 'AN' ),
                        array( 'CALCDR2-SPECIAL-FEATURE', 2, 'UN' ),
                        array( 'CALCDR2-CHARGE-MM', 2, 'BI' ),
                        array( 'CALCDR2-CHARGE-SS', 1, 'BI' ),
                        array( 'CALCDR2-ERROR-RETRY', 2, 'UN' ),
                        array( 'CALCDR2-ZERO-LD', 1, 'AN' ),
                        array( 'CALCDR2-NO-AIR', 1, 'AN' ),
                        array( 'CALCDR2-XNUMBER', 5, 'BI' ),
                        array( 'CALCDR2-CELL', 7, 'AN' ),
                        array( 'CALCDR2-CELL-FIL', 3, 'AN' ),
                        array( 'CALCDR2-CALL-DATE-YY-R', 1, 'BI' ),
                        array( 'CALCDR2-CALL-DATE-MM-R', 1, 'BI' ),
                        array( 'CALCDR2-CALL-DATE-DD-R', 1, 'BI' ),
                        array( 'CALCDR2-CALL-HH-R', 1, 'BI' ),
                        array( 'CALCDR2-CALL-MM-R', 1, 'BI' ),
                        array( 'CALCDR2-CALL-SS-R', 1, 'BI' ),
                        array( 'CALCDR2-CHARGE-MM-R', 2, 'BI' ),
                        array( 'CALCDR2-CHARGE-SS-R', 1, 'BI' ),
                        array( 'CALCDR2-CHARGE-UNITS', 3, 'BI' ),
                        array( 'CALCDR2-DISCARD-UNITS', 3, 'BI' ),
                        array( 'CALCDR2-SERV-SITE-ID', 2, 'BI' ),
                        array( 'CALCDR2-BILLED-SITE-ID', 2, 'BI' ),
                        array( 'CALCDR2-LD-ROUTE', 7, 'AN' ),
                        array( 'CALCDR2-LD-ROUTE-FIL', 3, 'AN' ),
                        array( 'CALCDR2-ERROR-CODE', 1, 'AN' ),
                        array( 'CALCDR2-ORG-IND', 1, 'AN' ),
                        array( 'CALCDR2-TOL-CARRIER', 2, 'BI' ),
                        array( 'CALCDR2-INCOMPLETE', 1, 'AN' ),
                        array( 'CALCDR2-BILLED-IND-OVERRIDE', 1, 'AN' ),
                        array( 'CALCDR2-SPLIT-BILL-IND', 1, 'AN' ),
                        array( 'CALCDR2-CALLER-ID-15', 5, 'BI' ),
                        array( 'CALCDR2-CALLER-ID-FIL', 2, 'BI' ),
                        array( 'CALCDR2-TMZN-INCR-DONE', 1, 'AN' ),
                        array( 'CALCDR2-BYP-CRS-MKT-RMG', 1, 'AN' ),
                        array( 'CALCDR2-SWITCH-FILE', 10, 'AN' ),
                        array( 'CALCDR2-LRN', 5, 'BI' ),
                        array( 'CALCDR2-TLDN', 5, 'BI' ),
                        array( 'CALCDR2-WNP-DIP-CHARGE-IND', 1, 'AN' ),
                        array( 'CALCDR2-TRAIL-FIL', 25, 'AN' ),
                        array( 'CALCDR2-CALLERID-PRESENT', 1, 'AN' ),
                        array( 'CALCDR2-CAMEL-SERVICES-LEVEL', 1, 'AN' ),
                        array( 'CALCDR2-INTL-ROAM-IND', 1, 'AN' ),
                        array( 'CALCDR2-CAMEL-SERV-KEY', 10, 'AN' ),
                        array( 'CALCDR2-CAMEL-DEST-ADDRESS', 15, 'AN' ),
                        array( 'CALCDR2-TRAIL-FIL-2', 22, 'AN' ) );



#################
#  SMSCDR Record
#
$smsCDRStruct = array( array( 'SMSCDR-SRVCLASS', 1, 'AN' ),
                       array( 'SMSCDR-BILLED-MDN', 5, 'BI' ),
                       array( 'SMSCDR-BILLED-MDN-FIL', 2, 'BI' ),
                       array( 'SMSCDR-CALL-DATE-YY', 1, 'BI' ),
                       array( 'SMSCDR-CALL-DATE-MM', 1, 'BI' ),
                       array( 'SMSCDR-CALL-DATE-DD', 1, 'BI' ),
                       array( 'SMSCDR-CALL-HH', 1, 'BI' ),
                       array( 'SMSCDR-CALL-MM', 1, 'BI' ),
                       array( 'SMSCDR-CALL-SS', 1, 'BI' ),
                       array( 'SMSCDR-BILLED-ESN', 5, 'BI' ),
                       array( 'SMSCDR-BILLED-ESN-FIL', 2, 'BI' ),
                       array( 'SMSCDR-BILLED-MIN-IMSI', 5, 'BI' ),
                       array( 'SMSCDR-BILLED-MIN-FIL', 2, 'BI' ),
                       array( 'SMSCDR-BILLED-EID', 6, 'BI' ),
                       array( 'SMSCDR-PASS-AMT', 5, 'SP' ),
                       array( 'SMSCDR-BILLED-IND', 2, 'UN' ),
                       array( 'SMSCDR-SERV-MARKET', 3, 'UN' ),
                       array( 'SMSCDR-DIALED-MDN', 18, 'AN' ),
                       array( 'SMSCDR-DES-CTRY', 5, 'AN' ),
                       array( 'SMSCDR-DES-MDN', 5, 'BI' ),
                       array( 'SMSCDR-DES-MDN-FIL', 2, 'BI' ),
                       array( 'SMSCDR-DES-MIN', 5, 'BI' ),
                       array( 'SMSCDR-DES-MIN-FIL', 2, 'BI' ),
                       array( 'SMSCDR-QUE-IND', 2, 'UN' ),
                       array( 'SMSCDR-DIRECTION', 2, 'AN' ),
                       array( 'SMSCDR-SPECIAL-FEATURE', 2, 'UN' ),
                       array( 'SMSCDR-MSG-LENGTH', 3, 'BI' ),
                       array( 'SMSCDR-ERROR-RETRY', 2, 'UN' ),
                       array( 'SMSCDR-ZERO-LD', 1, 'AN' ),
                       array( 'SMSCDR-NO-AIR', 1, 'AN' ),
                       array( 'SMSCDR-XNUMBER', 5, 'BI' ),
                       array( 'SMSCDR-CELL', 7, 'AN' ),
                       array( 'SMSCDR-CELL-FIL', 3, 'AN' ),
                       array( 'SMSCDR-CALL-DATE-YY-R', 1, 'BI' ),
                       array( 'SMSCDR-CALL-DATE-MM-R', 1, 'BI' ),
                       array( 'SMSCDR-CALL-DATE-DD-R', 1, 'BI' ),
                       array( 'SMSCDR-CALL-HH-R', 1, 'BI' ),
                       array( 'SMSCDR-CALL-MM-R', 1, 'BI' ),
                       array( 'SMSCDR-CALL-SS-R', 1, 'BI' ),
                       array( 'SMSCDR-MSG-LENGTH-R', 3, 'BI' ),
                       array( 'SMSCDR-CHARGE-UNITS', 3, 'BI' ),
                       array( 'SMSCDR-DISCARD-UNITS', 3, 'BI' ),
                       array( 'SMSCDR-SERV-SITE-ID', 2, 'BI' ),
                       array( 'SMSCDR-BILLED-SITE-ID', 2, 'BI' ),
                       array( 'SMSCDR-LD-ROUTE', 7, 'AN' ),
                       array( 'SMSCDR-LD-ROUTE-FIL', 3, 'AN' ),
                       array( 'SMSCDR-ERROR-CODE', 1, 'AN' ),
                       array( 'SMSCDR-ORG-IND', 1, 'AN' ),
                       array( 'SMSCDR-TOL-CARRIER', 2, 'BI' ),
                       array( 'SMSCDR-INCOMPLETE', 1, 'AN' ),
                       array( 'SMSCDR-BILLED-IND-OVERRIDE', 1, 'AN' ),
                       array( 'SMSCDR-SPLIT-BILL-IND', 1, 'AN' ),
                       array( 'SMSCDR-CALLER-ID', 5, 'BI' ),
                       array( 'SMSCDR-CALLER-ID-FIL', 2, 'BI' ),
                       array( 'SMSCDR-TMZN-INCR-DONE', 1, 'AN' ),
                       array( 'SMSCDR-BYP-CRS-MKT-RMG', 1, 'AN' ),
                       array( 'SMSCDR-SWITCH-FILE', 10, 'AN' ),
                       array( 'SMSCDR-LRN', 5, 'BI' ),
                       array( 'SMSCDR-TLDN', 5, 'BI' ),
                       array( 'SMSCDR-WNP-DIP-CHARGE-IND', 1, 'AN' ),
                       array( 'SMSCDR-CALLING-TON', 6, 'AN' ),
                       array( 'SMSCDR-CALLED-TON', 6, 'AN' ),
                       array( 'SMSCDR-DESCRIPTION', 40, 'AN' ),
                       array( 'SMSCDR-PRICE', 8, 'UN' ),
                       array( 'SMSCDR-LOCAL-TAX', 8, 'UN' ),
                       array( 'SMSCDR-STATE-TAX', 8, 'UN' ),
                       array( 'SMSCDR-FEDERAL-TAX', 8, 'UN' ),
                       array( 'SMSCDR-REFERENCE-ID', 11, 'AN' ),
                       array( 'SMSCDR-ACCT-LEVEL-BILL', 1, 'AN' ),
                       array( 'SMSCDR-UNITS', 2, 'UN' ),
                       array( 'SMSCDR-EVENT-TYPE', 2, 'AN' ),
                       array( 'SMSCDR-REC-TYPE', 1, 'AN' ),
                       array( 'SMSCDR-TRAIL-FIL', 24, 'AN' ) );


####################
#  DATCDR Struct
#
$datCDRStruct = array( array( 'DATCDR-SRVCLASS', 1, 'AN' ),
                       array( 'DATCDR-BILLED-MDN', 5, 'BI' ),
                       array( 'DATCDR-BILLED-MDN-FIL', 2, 'BI' ),
                       array( 'DATCDR-CALL-DATE-YY', 1, 'BI' ),
                       array( 'DATCDR-CALL-DATE-MM', 1, 'BI' ),
                       array( 'DATCDR-CALL-DATE-DD', 1, 'BI' ),
                       array( 'DATCDR-CALL-HH', 1, 'BI' ),
                       array( 'DATCDR-CALL-MM', 1, 'BI' ),
                       array( 'DATCDR-CALL-SS', 1, 'BI' ),
                       array( 'DATCDR-BILLED-ESN', 5, 'BI' ),
                       array( 'DATCDR-BILLED-ESN-FIL', 2, 'BI' ),
                       array( 'DATCDR-BILLED-MIN-IMSI', 5, 'BI' ),
                       array( 'DATCDR-BILLED-MIN-FIL', 2, 'BI' ),
                       array( 'DATCDR-BILLED-EID', 6, 'BI' ),
                       array( 'DATCDR-PASS-AMT', 5, 'SP' ),
                       array( 'DATCDR-BILLED-IND', 2, 'UN' ),
                       array( 'DATCDR-SERV-MARKET', 3, 'UN' ),
                       array( 'DATCDR-DIALED-MDN', 18, 'AN' ),
                       array( 'DATCDR-DES-CTRY', 5, 'AN' ),
                       array( 'DATCDR-DES-MDN', 5, 'BI' ),
                       array( 'DATCDR-DES-MDN-FIL', 2, 'BI' ),
                       array( 'DATCDR-DES-MIN', 5, 'BI' ),
                       array( 'DATCDR-DES-MIN-FIL', 2, 'BI' ),
                       array( 'DATCDR-QUE-IND', 2, 'UN' ),
                       array( 'DATCDR-DIRECTION', 2, 'AN' ),
                       array( 'DATCDR-SPECIAL-FEATURE', 2, 'UN' ),
                       array( 'DATCDR-MSG-LENGTH', 3, 'BI' ),
                       array( 'DATCDR-ERROR-RETRY', 2, 'UN' ),
                       array( 'DATCDR-ZERO-LD', 1, 'AN' ),
                       array( 'DATCDR-NO-AIR', 1, 'AN' ),
                       array( 'DATCDR-XNUMBER', 5, 'BI' ),
                       array( 'DATCDR-CELL', 7, 'AN' ),
                       array( 'DATCDR-CELL-FIL', 3, 'AN' ),
                       array( 'DATCDR-CALL-DATE-YY-R', 1, 'BI' ),
                       array( 'DATCDR-CALL-DATE-MM-R', 1, 'BI' ),
                       array( 'DATCDR-CALL-DATE-DD-R', 1, 'BI' ),
                       array( 'DATCDR-CALL-HH-R', 1, 'BI' ),
                       array( 'DATCDR-CALL-MM-R', 1, 'BI' ),
                       array( 'DATCDR-CALL-SS-R', 1, 'BI' ),
                       array( 'DATCDR-MSG-LENGTH-R', 3, 'BI' ),
                       array( 'DATCDR-CHARGE-KB', 3, 'BI' ),
                       array( 'DATCDR-DISCARD-KB', 3, 'BI' ),
                       array( 'DATCDR-SERV-SITE-ID', 2, 'BI' ),
                       array( 'DATCDR-BILLED-SITE-ID', 2, 'BI' ),
                       array( 'DATCDR-LD-ROUTE', 7, 'AN' ),
                       array( 'DATCDR-LD-ROUTE-FIL', 3, 'AN' ),
                       array( 'DATCDR-ERROR-CODE', 1, 'AN' ),
                       array( 'DATCDR-ORG-IND', 1, 'AN' ),
                       array( 'DATCDR-TOL-CARRIER', 2, 'BI' ),
                       array( 'DATCDR-INCOMPLETE', 1, 'AN' ),
                       array( 'DATCDR-BILLED-IND-OVERRIDE', 1, 'AN' ),
                       array( 'DATCDR-SPLIT-BILL-IND', 1, 'AN' ),
                       array( 'DATCDR-CALLER-ID', 5, 'BI' ),
                       array( 'DATCDR-CALLER-ID-FIL', 2, 'BI' ),
                       array( 'DATCDR-TMZN-INCR-DONE', 1, 'AN' ),
                       array( 'DATCDR-BYP-CRS-MKT-RMG', 1, 'AN' ),
                       array( 'DATCDR-SWITCH-FILE', 10, 'AN' ),
                       array( 'DATCDR-LRN', 5, 'BI' ),
                       array( 'DATCDR-TLDN', 5, 'BI' ),
                       array( 'DATCDR-WNP-DIP-CHARGE-IND', 1, 'AN' ),
                       array( 'DATCDR-CALLING-TON', 6, 'AN' ),
                       array( 'DATCDR-CALLED-TON', 6, 'AN' ),
                       array( 'DATCDR-DESCRIPTION', 40, 'AN' ),
                       array( 'DATCDR-PRICE', 8, 'UN' ),
                       array( 'DATCDR-LOCAL-TAX', 8, 'UN' ),
                       array( 'DATCDR-STATE-TAX', 8, 'UN' ),
                       array( 'DATCDR-FEDERAL-TAX', 8, 'UN' ),
                       array( 'DATCDR-REFERENCE-ID', 11, 'AN' ),
                       array( 'DATCDR-ACCT-LEVEL-BILL', 1, 'AN' ),
                       array( 'DATCDR-KB-MO-OT', 3, 'BI' ),
                       array( 'DATCDR-KB-MT-IN', 3, 'BI' ),
                       array( 'DATCDR-DOMAIN', 15, 'AN' ),
                       array( 'DATCDR-TRAIL-FIL', 8, 'AN' ) );

########################
#  Cingular Fixed Record
#
$cingFixStruct = array( array( 'cngcdr-billable-number', 10, 'UN' ),
                        array( 'cngcdr-other-party-number', 10, 'UN' ),
                        array( 'cngcdr-billable-overflow', 05, 'UN' ),
                        array( 'cngcdr-other-party-overflow', 05, 'UN' ),
                        array( 'cngcdr-record-date', 06, 'UN' ),
                        array( 'cngcdr-connect-time', 06, 'UN' ),
                        array( 'cngcdr-record-type', 02, 'UN' ),
                        array( 'cngcdr-time-zone-ind', 02, 'UN' ),
                        array( 'cngcdr-daylight-savings-ind', 01, 'UN' ),
                        array( 'cngcdr-call-direction-ind', 01, 'UN' ),
                        array( 'cngcdr-call-completion-ind', 01, 'UN' ),
                        array( 'cngcdr-cellsite-id', 05, 'UN' ),
                        array( 'cngcdr-serv-sid', 05, 'UN' ),
                        array( 'cngcdr-originating-location', 12, 'AN' ),
                        array( 'cngcdr-terminating-location', 12, 'AN' ),
                        array( 'cngcdr-sc-settlement-code', 01, 'AN' ),
                        array( 'cngcdr-call-features', 01, 'UN' ),
                        array( 'cngcdr-plmn-code', 05, 'AN' ),
                        array( 'cngcdr-location-area-code', 05, 'UN' ),
                        array( 'cngcdr-network-technology-type', 03, 'AN' ),
                        array( 'cngcdr-home-roam-ind', 01, 'AN' ),
                        array( 'cngcdr-imsi',15, 'UN' ),
                        array( 'cngcdr-lrn', 10, 'UN' ),
                        array( 'cngcdr-jip', 15, 'UN' ),
                        array( 'cngcdr-elapsed-time', 06, 'UN' ),
                        array( 'cngcdr-future-usage-field', 44, 'AN' ),
                        array( 'newline', 01, 'AN' ) );



########################
#  Ciber Type 22 Record
#
$cib0222Struct = array(
                 array( 'C0222-RCD                      ' , 02, 'UN' ),
                 array( 'C0222-RETURN-CODE              ' , 01, 'UN' ),
                 array( 'C0222-RETURN-REASON            ' , 02, 'UN' ),
                 array( 'C0222-INVALID-FLD-IND          ' , 03, 'UN' ),
                 array( 'C0222-HOME-SID                 ' , 05, 'UN' ),
                 array( 'C0222-MOBIL-ID-IND             ' , 01, 'UN' ),
                 array( 'C0222-MOBIL-ID                 ' , 15, 'UN' ),
                 array( 'C0222-MSISDN-MDN-LEN           ' , 02, 'UN' ),
                 array( 'C0222-MSISDN-MDN               ' , 15, 'UN' ),
                 array( 'C0222-ESN-IMEI-IND             ' , 01, 'UN' ),
                 array( 'C0222-ESN                      ' , 19, 'UN' ),
                 array( 'C0222-SERV-SID                 ' , 05, 'UN' ),
                 array( 'C0222-TOTAL-AMT                ' , 11, 'UN' ),
                 array( 'C0222-STATE-TAX                ' , 11, 'UN' ),
                 array( 'C0222-LOCAL-TAX                ' , 11, 'UN' ),
                 array( 'C0222-CALL-DATE                ' , 06, 'UN' ),
                 array( 'C0222-DIRECTION                ' , 01, 'UN' ),
                 array( 'C0222-COMPLETE-IND             ' , 01, 'UN' ),
                 array( 'C0222-TERMINATE-IND            ' , 01, 'UN' ),
                 array( 'C0222-CALLER-ID-LEN            ' , 02, 'UN' ),
                 array( 'C0222-CALLER-ID                ' , 15, 'UN' ),
                 array( 'C0222-CALLED-NUM-LEN           ' , 02, 'UN' ),
                 array( 'C0222-CALLED-NUMBER            ' , 10, 'UN' ),
                 array( 'C0222-CALLED-OVER              ' , 05, 'UN' ),
                 array( 'C0222-LOC-ROUTE-NUM-LEN        ' , 02, 'UN' ),
                 array( 'C0222-LOC-ROUTE-NUM            ' , 15, 'UN' ),
                 array( 'C0222-TLDN-LEN                 ' , 02, 'UN' ),
                 array( 'C0222-TLDN                     ' , 15, 'UN' ),
                 array( 'C0222-CURRENCY                 ' , 02, 'UN' ),
                 array( 'C0222-SYSTEM-RESERVED-01       ' , 02, 'AN' ),
                 array( 'C0222-ORIG-BATCH-SEQ-NUM       ' , 03, 'UN' ),
                 array( 'C0222-INITIAL-CELL             ' , 11, 'UN' ),
                 array( 'C0222-TIME-ZONE                ' , 02, 'UN' ),
                 array( 'C0222-DAYLITE-SAV              ' , 01, 'UN' ),
                 array( 'C0222-MSG-UNITS                ' , 10, 'UN' ),
                 array( 'C0222-CONNECT-TIME             ' , 06, 'UN' ),
                 array( 'C0222-CHG-TIME                 ' , 06, 'UN' ),
                 array( 'C0222-ELP-TIME                 ' , 06, 'UN' ),
                 array( 'C0222-RATE-PERIOD              ' , 02, 'UN' ),
                 array( 'C0222-MULTI-RATE               ' , 01, 'UN' ),
                 array( 'C0222-AIR-AMT                  ' , 11, 'UN' ),
                 array( 'C0222-OTHER-CHG-NO1-IND        ' , 02, 'AN' ),
                 array( 'C0222-OTHER-CHG-NO1            ' , 11, 'UN' ),
                 array( 'C0222-SYS-RESERVE              ' , 13, 'AN' ),
                 array( 'C0222-PRINTED-CALL             ' , 15, 'AN' ),
                 array( 'C0222-FRAUD-IND                ' , 02, 'AN' ),
                 array( 'C0222-SUB-FRAUD-IND            ' , 01, 'AN' ),
                 array( 'C0222-SPECIAL-FEATURE          ' , 05, 'AN' ),
                 array( 'C0222-CALLED-CITY              ' , 10, 'AN' ),
                 array( 'C0222-CALLED-STATE             ' , 02, 'AN' ),
                 array( 'C0222-CALLED-COUNTRY           ' , 03, 'AN' ),
                 array( 'C0222-SERV-CITY                ' , 10, 'AN' ),
                 array( 'C0222-SERV-STATE               ' , 02, 'AN' ),
                 array( 'C0222-SERV-COUNTRY             ' , 03, 'AN' ),
                 array( 'C0222-TOL-CONNECT-TIME         ' , 06, 'UN' ),
                 array( 'C0222-TOL-CHG-TIME             ' , 06, 'UN' ),
                 array( 'C0222-TOL-ELP-TIME             ' , 06, 'UN' ),
                 array( 'C0222-TOL-TARIF                ' , 02, 'UN' ),
                 array( 'C0222-TOL-RATE-PERIOD          ' , 02, 'UN' ),
                 array( 'C0222-TOL-MULTI-RATE           ' , 01, 'UN' ),
                 array( 'C0222-TOL-RATE-CLASS           ' , 01, 'UN' ),
                 array( 'C0222-TOL-RATE-POINT-LEN-IND   ' , 02, 'UN' ),
                 array( 'C0222-TOL-RATE-POINT           ' , 10, 'UN' ),
                 array( 'C0222-TOL-AMT                  ' , 11, 'UN' ),
                 array( 'C0222-TOL-STATE-TAX            ' , 11, 'UN' ),
                 array( 'C0222-TOL-LOCAL-TAX            ' , 11, 'UN' ),
                 array( 'C0222-TOL-CARRIER              ' , 05, 'UN' ),
                 array( 'C0222-REROUT-FLAG              ' , 01, 'AN' ),
                 array( 'C0222-HRMR-REJECT-FLAG         ' , 01, 'AN' ),
                 array( 'C0222-HRMR-REJECT-CNT          ' , 02, 'UN' ),
                 array( 'C0222-V-CORD                   ' , 05, 'UN' ),
                 array( 'C0222-H-CORD                   ' , 05, 'UN' ),
                 array( 'C0222-LOCAL-RESERVE            ' , 61, 'AN' ),
                 array( 'C0222-VRSN-FILE-NAME           ' , 10, 'AN' ),
                 array( 'C0222-CAMEL-SERVICE-LEVEL      ' , 01, 'AN' ),
                 array( 'C0222-INTL-ROAM-IND            ' , 01, 'AN' ),
                 array( 'C0222-CAMEL-SERV-KEY           ' , 10, 'AN' ),
                 array( 'C0222-CAMEL-DEST-ADDR          ' , 15, 'AN' ),
                 array( 'C0222-SYS-RESERVE-2            ' , 38, 'AN' ),
                 array( 'C0222-filler                   ' , 20, 'AN' ) );


########################
#  UDRCH Structure
#
$udrsdStruct = array( 
               array( 'udrsd-type-id                  ', 5, 'AN' ),
               array( 'udrsd-creator                  ', 15, 'AN' ),
               array( 'udrsd-creation-datetime        ', 12, 'AN' ),
               array( 'udrsd-air-amount               ', 11, 'AN' ),
               array( 'udrsd-air-local-tax            ', 11, 'AN' ),
               array( 'udrsd-air-state-tax            ', 11, 'AN' ),
               array( 'udrsd-air-rate-period          ', 2, 'AN' ),
               array( 'udrsd-apn-network-info         ', 20, 'AN' ),
               array( 'udrsd-apn-operator-info        ', 20, 'AN' ),
               array( 'udrsd-apn-selection-mode       ', 20, 'AN' ),
               array( 'udrsd-billed-eid               ', 12, 'AN' ),
               array( 'udrsd-billed-esn               ', 15, 'AN' ),
               array( 'udrsd-billed-imei              ', 15, 'AN' ),
               array( 'udrsd-billed-imsi              ', 15, 'AN' ),
               array( 'udrsd-billed-mdn-msisd         ', 16, 'AN' ),
               array( 'udrsd-billed-esn-meid          ', 20, 'AN' ),
               array( 'udrsd-billed-meid-chk-digit    ', 02, 'AN' ),
               array( 'udrsd-billed-min               ', 10, 'AN' ),
               array( 'udrsd-billed-sim               ', 20, 'AN' ),
               array( 'udrsd-billed-celltower         ', 10, 'AN' ),
               array( 'udrsd-call-end-reason          ', 2, 'AN' ),
               array( 'udrsd-called-ton               ', 6, 'AN' ),
               array( 'udrsd-caller-id                ', 16, 'AN' ),
               array( 'udrsd-caller-id-block          ', 1, 'AN' ),
               array( 'udrsd-camel-service-key        ', 10, 'AN' ),
               array( 'udrsd-camel-service-level      ', 1, 'AN' ),
               array( 'udrsd-camel-dest-number        ', 32, 'AN' ),
               array( 'udrsd-camel-invocation-fee     ', 11, 'AN' ),
               array( 'udrsd-camel-tax                ', 11, 'AN' ),
               array( 'udrsd-change-condition         ', 01, 'AN' ),
               array( 'udrsd-change-time              ', 12, 'AN' ),
               array( 'udrsd-charge-in-packets        ', 10, 'AN' ),
               array( 'udrsd-charge-in-units          ', 10, 'AN' ),
               array( 'udrsd-charge-out-packets       ', 10, 'AN' ),
               array( 'udrsd-charge-out-units         ', 10, 'AN' ),
               array( 'udrsd-charge-packets           ', 10, 'AN' ),
               array( 'udrsd-charging-characteristics ', 1, 'AN' ),
               array( 'udrsd-charging-info            ', 15, 'AN' ),
               array( 'udrsd-ciber-type               ', 2, 'AN' ),
               array( 'udrsd-coord-h                  ', 5, 'AN' ),
               array( 'udrsd-coord-v                  ', 5, 'AN' ),
               array( 'udrsd-currency                 ', 2, 'AN' ),
               array( 'udrsd-datetime-end             ', 16, 'AN' ),
               array( 'udrsd-datetime-connect         ', 16, 'AN' ),
               array( 'udrsd-datetime-ring            ', 16, 'AN' ),
               array( 'udrsd-datetime-start           ', 16, 'AN' ),
               array( 'udrsd-daylight-sav             ', 1, 'AN' ),
               array( 'udrsd-description              ', 30, 'AN' ),
               array( 'udrsd-discard-in-units         ', 9, 'AN' ),
               array( 'udrsd-discard-out-units        ', 9, 'AN' ),
               array( 'udrsd-dst-city                 ', 10, 'AN' ),
               array( 'udrsd-dst-country              ', 4, 'AN' ),
               array( 'udrsd-dst-country-numeric      ', 4, 'AN' ),
               array( 'udrsd-dst-dialed               ', 32, 'AN' ),
               array( 'udrsd-dst-imsi                 ', 15, 'AN' ),
               array( 'udrsd-dst-lrn                  ', 10, 'AN' ),
               array( 'udrsd-dst-mdn-msisd            ', 16, 'AN' ),
               array( 'udrsd-dst-min                  ', 10, 'AN' ),
               array( 'udrsd-dst-state                ', 02, 'AN' ),
               array( 'udrsd-duration                 ', 8, 'AN' ),
               array( 'udrsd-duration-ring            ', 8, 'AN' ),
               array( 'udrsd-duration-connect         ', 8, 'AN' ),
               array( 'udrsd-duration-unit            ', 2, 'AN' ),
               array( 'udrsd-billed-market            ', 3, 'AN' ),
               array( 'udrsd-market-npa               ', 6, 'AN' ),
               array( 'udrsd-event                    ', 3, 'AN' ),
               array( 'udrsd-file-reference           ', 11, 'AN' ),
               array( 'udrsd-ggsn-address-used        ', 20, 'AN' ),
               array( 'udrsd-home-pmn                 ', 5, 'AN' ),
               array( 'udrsd-home-rmr-rerate          ', 1, 'AN' ),
               array( 'udrsd-home-sid                 ', 5, 'AN' ),
               array( 'udrsd-interconnect             ', 2, 'AN' ),
               array( 'udrsd-interconnect-amount      ', 11, 'AN' ),
               array( 'udrsd-interconnect-flag        ', 1, 'AN' ),
               array( 'udrsd-interconnect-toll        ', 1, 'AN' ),
               array( 'udrsd-jip                      ', 10, 'AN' ),
               array( 'udrsd-ld-route                 ', 10, 'AN' ),
               array( 'udrsd-local-rec-seq-num        ', 9, 'AN' ),
               array( 'udrsd-location-area-code       ', 3, 'AN' ),
               array( 'udrsd-message-length           ', 7, 'AN' ),
               array( 'udrsd-toll-mileage             ', 5, 'AN' ),
               array( 'udrsd-ms-network-capability    ', 2, 'AN' ),
               array( 'udrsd-network-initiation       ', 2, 'AN' ),
               array( 'udrsd-no-air-flag              ', 1, 'AN' ),
               array( 'udrsd-other-amount             ', 11, 'AN' ),
               array( 'udrsd-other-local-tax          ', 11, 'AN' ),
               array( 'udrsd-other-state-tax          ', 11, 'AN' ),
               array( 'udrsd-pdp-type                 ', 20, 'AN' ),
               array( 'udrsd-pmn-test-active-ind      ', 1, 'AN' ),
               array( 'udrsd-price                    ', 11, 'AN' ),
               array( 'udrsd-qos-neg-delay            ', 1, 'AN' ),
               array( 'udrsd-qos-neg-mean-throughput  ', 2, 'AN' ),
               array( 'udrsd-qos-neg-peak-throughput  ', 1, 'AN' ),
               array( 'udrsd-qos-neg-precedence       ', 1, 'AN' ),
               array( 'udrsd-qos-neg-reliability      ', 1, 'AN' ),
               array( 'udrsd-qos-req-delay            ', 1, 'AN' ),
               array( 'udrsd-qos-req-mean-throughput  ', 2, 'AN' ),
               array( 'udrsd-qos-req-peak-throughput  ', 1, 'AN' ),
               array( 'udrsd-qos-req-precedence       ', 1, 'AN' ),
               array( 'udrsd-qos-req-reliability      ', 1, 'AN' ),
               array( 'udrsd-rate-center              ', 5, 'AN' ),
               array( 'udrsd-rec-extensions           ', 15, 'AN' ),
               array( 'udrsd-rnc-unsent-downlink-vol  ', 10, 'AN' ),
               array( 'udrsd-routing-area             ', 10, 'AN' ),
               array( 'udrsd-served-market            ', 3, 'AN' ),
               array( 'udrsd-served-pdp-addr          ', 20, 'AN' ),
               array( 'udrsd-served-pmn               ', 5, 'AN' ),
               array( 'udrsd-served-sid               ', 5, 'AN' ),
               array( 'udrsd-service-feature          ', 2, 'AN' ),
               array( 'udrsd-service-sub-feature      ', 4, 'AN' ),
               array( 'udrsd-sgsn-address             ', 20, 'AN' ),
               array( 'udrsd-sgsn-change              ', 1, 'AN' ),
               array( 'udrsd-src-city                 ', 10, 'AN' ),
               array( 'udrsd-src-country              ', 4, 'AN' ),
               array( 'udrsd-src-country-numeric      ', 4, 'AN' ),
               array( 'udrsd-src-infra-hw-info        ', 20, 'AN' ),
               array( 'udrsd-src-lrn                  ', 10, 'AN' ),
               array( 'udrsd-src-npa-nxx              ', 6, 'AN' ),
               array( 'udrsd-src-state                ', 2, 'AN' ),
               array( 'udrsd-surcharge-amount         ', 11, 'AN' ),
               array( 'udrsd-surcharge-info           ', 2, 'AN' ),
               array( 'udrsd-surcharge-misc-amount    ', 11, 'AN' ),
               array( 'udrsd-surcharge-misc-info      ', 2, 'AN' ),
               array( 'udrsd-switch-file-batch        ', 20, 'AN' ),
               array( 'udrsd-switch-file              ', 10, 'AN' ),
               array( 'udrsd-timezone-incr-done       ', 1, 'AN' ),
               array( 'udrsd-tldn                     ', 10, 'AN' ),
               array( 'udrsd-toll-amount              ', 11, 'AN' ),
               array( 'udrsd-toll-carrier             ', 5, 'AN' ),
               array( 'udrsd-toll-local-tax           ', 11, 'AN' ),
               array( 'udrsd-toll-multi-rate          ', 1, 'AN' ),
               array( 'udrsd-toll-period              ', 2, 'AN' ),
               array( 'udrsd-toll-spec-type           ', 2, 'AN' ),
               array( 'udrsd-toll-state-tax           ', 11, 'AN' ),
               array( 'udrsd-toll-tarif               ', 2, 'AN' ),
               array( 'udrsd-toll-tax                 ', 1, 'AN' ),
               array( 'udrsd-toll-wide-area           ', 3, 'AN' ),
               array( 'udrsd-trunk-group-incoming     ', 10, 'AN' ),
               array( 'udrsd-trunk-group-outgoing     ', 10, 'AN' ),
               array( 'udrsd-wnp-dip-charge-info      ', 4, 'AN' ),
               array( 'udrsd-wnp-dip-charge-ind       ', 1, 'AN' ),
               array( 'udrsd-world-timezone           ', 2, 'AN' ),
               array( 'udrsd-zero-ld-chg-flag         ', 1, 'AN' ),
               array( 'udrsd-org-coord-h              ', 5, 'AN' ),
               array( 'udrsd-org-coord-v              ', 5, 'AN' ) ); 


$ntStruct = array( array( 'nt_rcd',                    2, 'AN' ), # 0
                   array( 'nt_entry_code',             2, 'AN' ), # 1
                   array( 'nt_verinfo',                4, 'AN' ), # 2
                   array( 'nt_ofeatcd',                8, 'AN' ), # 3
                   array( 'nt_tfeatcd',               12, 'AN' ), # 4
                   array( 'nt_calling_number',        10, 'AN' ), # 5
                   array( 'nt_call_nbr_fil',          14, 'AN' ), # 6
                   array( 'nt_calling_system',         5, 'AN' ), # 7
                   array( 'nt_org_msa',                3, 'AN' ), # 8
                   array( 'nt_org_roamer_ind',         1, 'AN' ), # 9
                   array( 'nt_org_sta_class_mark',     2, 'AN' ), # 10
                   array( 'nt_calling_cat',            1, 'AN' ), # 11
                   array( 'nt_org_chnl_type_cap',      2, 'AN' ), # 12
                   array( 'nt_org_chnl_used',          2, 'AN' ), # 13
                   array( 'nt_calling_esn',           11, 'AN' ), # 14
                   array( 'nt_called_svc',             1, 'AN' ), # 15
                   array( 'nt_credcard',              16, 'AN' ), # 16
                   array( 'nt_dialed_number',         32, 'AN' ), # 17
                   array( 'nt_called_number',         30, 'AN' ), # 18
                   array( 'nt_filler_18',              2, 'AN' ), # 19
                   array( 'nt_called_system',          5, 'AN' ), # 20
                   array( 'nt_term_msa',               3, 'AN' ), # 21
                   array( 'nt_term_roam_ind',          1, 'AN' ), # 22
                   array( 'nt_term_sta_class_mark',    2, 'AN' ), # 23
                   array( 'nt_clldcat',                1, 'AN' ), # 24
                   array( 'nt_term_chnl_cap',          2, 'AN' ), # 25
                   array( 'nt_term_chnl_used',         2, 'AN' ), # 26
                   array( 'nt_called_esn',            11, 'AN' ), # 27
                   array( 'nt_call_type',              1, 'AN' ), # 28
                   array( 'nt_billing_number',        10, 'AN' ), # 29
                   array( 'nt_bill_nbr_fill',         14, 'AN' ), # 30
                   array( 'nt_acct_code',              8, 'AN' ), # 31
                   array( 'nt_billing_cat',            1, 'AN' ), # 32
                   array( 'nt_filler_01',              3, 'AN' ), # 33
                   array( 'nt_authcode',               7, 'AN' ), # 34
                   array( 'nt_orig_reg_psid_ind',      1, 'AN' ), # 35
                   array( 'nt_treatment_code',         3, 'AN' ), # 36
                   array( 'nt_term_reg_psid_ind',      1, 'AN' ), # 37
                   array( 'nt_billing_esn',           11, 'AN' ), # 38
                   array( 'nt_event_info_digit',       1, 'AN' ), # 39
                   array( 'nt_orig_nwkbid',           19, 'AN' ), # 40
                   array( 'nt_orig_mtx_call_type',     1, 'AN' ), # 41
                   array( 'nt_term_nwkbid',           19, 'AN' ), # 42
                   array( 'nt_term_mtx_call_type',     1, 'AN' ), # 43
                   array( 'nt_first_org_clli',         4, 'AN' ), # 44
                   array( 'nt_first_org_member',       4, 'AN' ), # 45
                   array( 'nt_prev_org_clli',          4, 'AN' ), # 46
                   array( 'nt_prev_org_member',        4, 'AN' ), # 47
                   array( 'nt_last_org_clli',          4, 'AN' ), # 48
                   array( 'nt_last_org_member',        4, 'AN' ), # 49
                   array( 'nt_org_time',               9, 'AN' ), # 50
                   array( 'nt_handoffs',               3, 'AN' ), # 51
                   array( 'nt_first_term_clli',        4, 'AN' ), # 52
                   array( 'nt_first_term_member',      4, 'AN' ), # 53
                   array( 'nt_prev_term_clli',         4, 'AN' ), # 54
                   array( 'nt_prev_term_member',       4, 'AN' ), # 55
                   array( 'nt_last_term_clli',         4, 'AN' ), # 56
                   array( 'nt_last_term_member',       4, 'AN' ), # 57
                   array( 'nt_disc_time',              9, 'AN' ), # 58
                   array( 'nt_serv_translation',       3, 'AN' ), # 59
                   array( 'nt_call_duration_ss',       6, 'AN' ), # 60
                   array( 'nt_filler_60',              2, 'AN' ), # 61
                   array( 'nt_orig_srv_ind',           4, 'AN' ), # 62
                   array( 'nt_org_hold',               6, 'AN' ), # 63
                   array( 'nt_ix',                     2, 'AN' ), # 64
                   array( 'nt_term_hold',              6, 'AN' ), # 65
                   array( 'nt_completion_code',        2, 'AN' ), # 66
                   array( 'nt_oextsys',                6, 'AN' ), # 67
                   array( 'nt_answer_type',            2, 'AN' ), # 68
                   array( 'nt_textsys',                6, 'AN' ), # 69
                   array( 'nt_filler_69',              2, 'AN' ), # 70
                   array( 'nt_tsvcind',                4, 'AN' ), # 71
                   array( 'nt_orig_cdma_srv_opt',      4, 'AN' ), # 72
                   array( 'nt_term_cdma_srv_opt',      4, 'AN' ), # 73
                   array( 'nt_orig_reg_psid_val_hex',  6, 'AN' ), # 74
                   array( 'nt_filler_02',              2, 'AN' ), # 75
                   array( 'nt_term_reg_psid_val_hex',  6, 'AN' ), # 76
                   array( 'nt_filler_03',              2, 'AN' ), # 77
                   array( 'nt_term_number',           24, 'AN' ), # 78
                   array( 'nt_answer_time',            9, 'AN' ), # 79
                   array( 'nt_filler_04',              3, 'AN' ), # 80
                   array( 'nt_carrier_idc',            4, 'AN' ), # 81
                   array( 'nt_custtype',               3, 'AN' ), # 82
                   array( 'nt_filler_05',              1, 'AN' ), # 83
                   array( 'nt_redirecting_number',    24, 'AN' ), # 84
                   array( 'nt_charge_band_number',     3, 'AN' ), # 85
                   array( 'nt_filler_85',              1, 'AN' ), # 86
                   array( 'nt_clgmsid',               15, 'AN' ), # 87
                   array( 'nt_lrnsrc',                 1, 'AN' ), # 88
                   array( 'nt_cldmsid',               15, 'AN' ), # 89
                   array( 'nt_nptrkqry',               1, 'AN' ), # 90
                   array( 'nt_blgmsid',               15, 'AN' ), # 91
                   array( 'nt_npstatus',               1, 'AN' ), # 92
                   array( 'nt_termlrn',               18, 'AN' ), # 93
                   array( 'nt_filler_06',              2, 'AN' ), # 94
                   array( 'nt_origip',                 6, 'AN' ), # 95
                   array( 'nt_filler_07',              2, 'AN' ), # 96
                   array( 'nt_orig_feats',            32, 'AN' ), # 97 /* blown out */
                   array( 'nt_term_feats',            32, 'AN' ), # 98 /* blown out */
                   array( 'nt_orig_date_greg',         8, 'AN' ), # 99  /* CCYYMMDD */
                   array( 'nt_disc_date_greg',         8, 'AN' ), # 100 /* CCYYMMDD */
                   array( 'nt_answ_date_greg',         8, 'AN' ), # 101 /* CCYYMMDD */
                   array( 'nt_rerout',                 1, 'AN' ), # 102 /* rerout ind */
                   array( 'nt_serv_feat',              2, 'AN' ), # 103 /* spec feat */
                   array( 'nt_caller_id_a',            1, 'AN' ), # 104 /* call id p */
                   array( 'nt_caller_id_b',            1, 'AN' ), # 105 /* call id p */
                   array( 'nt_enode',                  3, 'AN' ), # 106
                   array( 'nt_incoming_rte',          10, 'AN' ), # 107  /* in route */
                   array( 'nt_outgoing_rte',          10, 'AN' ), # 108  /* out route */
                   array( 'nt_a_bill_ind',             1, 'AN' ), # 109
                   array( 'nt_b_bill_ind',             1, 'AN' ), # 110
                   array( 'nt_incomplete',             1, 'AN' ), # 111
                   array( 'nt_vm_outdial',             1, 'AN' ), # 112
                   array( 'nt_call_duration',          8, 'AN' ), # 113  /* MMMMMSST */
                   array( 'nt_ring_duration',          8, 'AN' ), # 114  /* MMMMMSST */
                   array( 'nt_rej_code',               1, 'AN' ), # 115
                   array( 'nt_a_sub_min',             10, 'AN' ), # 116
                   array( 'nt_a_sub_mdn',             16, 'AN' ), # 117     /*12-20-05*/
                   array( 'nt_b_sub_num',             32, 'AN' ), # 118
                   array( 'nt_b_sub_min',             10, 'AN' ), # 119
                   array( 'nt_b_sub_mdn',             16, 'AN' ), # 120     /*12-20-05*/
                   array( 'nt_num_translate',          1, 'AN' ), # 121
                   array( 'nt_bill_c',                 1, 'AN' ), # 122
                   array( 'nt_a_chg',                  1, 'AN' ), # 123
                   array( 'nt_b_chg',                  1, 'AN' ), # 124
                   array( 'nt_dialed_work',           32, 'AN' ), # 125
                   array( 'nt_call_identifier_block', 10, 'AN' ), # 126
                   array( 'nt_call_identifier_rcd_cnt',5, 'AN' ), # 127
                   array( 'nt_lf', 1, 'AN' ) );

# added                                                     # 07-10-06
$ntgsmStructR02 = 
    array (
    array ( 'ntgsmr02_hexadecimal_id',              2, 'AN' ), # 0
    array ( 'ntgsmr02_release_release',             2, 'AN' ), # 1
    array ( 'ntgsmr02_release_version',             2, 'AN' ), # 2
    array ( 'ntgsmr02_structure_code_module_ind',   1, 'AN' ), # 3
    array ( 'ntgsmr02_structure_code',              4, 'AN' ), # 4
    array ( 'ntgsmr02_call_type_code',              3, 'AN' ), # 5
    array ( 'ntgsmr02_study_ind',                   1, 'AN' ), # 6
    array ( 'ntgsmr02_call_forward_ind',            1, 'AN' ), # 7
    array ( 'ntgsmr02_calling_party_nt',            1, 'AN' ), # 8
    array ( 'ntgsmr02_calling_party_npi_ei',        1, 'AN' ), # 9
    array ( 'ntgsmr02_calling_party_npi_noai',      1, 'AN' ), # 10
    array ( 'ntgsmr02_calling_party_npi',           2, 'AN' ), # 11
    array ( 'ntgsmr02_calling_party_string',       21, 'AN' ), # 12
    array ( 'ntgsmr02_calling_number_nt',           1, 'AN' ), # 13
    array ( 'ntgsmr02_calling_number_npi_ei',       1, 'AN' ), # 14
    array ( 'ntgsmr02_calling_number_npi_noai',     1, 'AN' ), # 15
    array ( 'ntgsmr02_calling_number_npi',          2, 'AN' ), # 16
    array ( 'ntgsmr02_calling_number_string',      21, 'AN' ), # 17
    array ( 'ntgsmr02_called_number_nt',            1, 'AN' ), # 18
    array ( 'ntgsmr02_called_number_npi_ei',        1, 'AN' ), # 19
    array ( 'ntgsmr02_called_number_npi_noai',      1, 'AN' ), # 20
    array ( 'ntgsmr02_called_number_npi',           2, 'AN' ), # 21
    array ( 'ntgsmr02_called_number_string',       31, 'AN' ), # 22
    array ( 'ntgsmr02_calling_equipment',          21, 'AN' ), # 23
    array ( 'ntgsmr02_additional_information',      1, 'AN' ), # 24
    array ( 'ntgsmr02_channel_allocation_time',    16, 'AN' ), # 25
    array ( 'ntgsmr02_answer_time',                16, 'AN' ), # 26
    array ( 'ntgsmr02_disconnect_time',            16, 'AN' ), # 27
    array ( 'ntgsmr02_release_time',               16, 'AN' ), # 28
    array ( 'ntgsmr02_off_air_call_setup',          1, 'AN' ), # 29
    array ( 'ntgsmr02_half_rate_in_use',            1, 'AN' ), # 30
    array ( 'ntgsmr02_cause_for_termination',       2, 'AN' ), # 31
    array ( 'ntgsmr02_call_reference',              7, 'AN' ), # 32
    array ( 'ntgsmr02_ms_classmark',               15, 'AN' ), # 33
    array ( 'ntgsmr02_classmark_timestamp',        16, 'AN' ), # 34
    array ( 'ntgsmr02_dialled_digits_nt',           1, 'AN' ), # 35
    array ( 'ntgsmr02_dialled_digits_string',      31, 'AN' ), # 36
    array ( 'ntgsmr02_outgoing_trunk_group',        5, 'AN' ), # 37
    array ( 'ntgsmr02_outgoing_trunk_member',       5, 'AN' ), # 38
    array ( 'ntgsmr02_outgoing_route_group',        3, 'AN' ), # 39
    array ( 'ntgsmr02_outgoing_trunk_seizure',     16, 'AN' ), # 40
    array ( 'ntgsmr02_calling_subscriber_category', 3, 'AN' ), # 41
    array ( 'ntgsmr02_call_ind',                    1, 'AN' ), # 42
    array ( 'ntgsmr02_call_duration',               7, 'AN' ), # 43
    array ( 'ntgsmr02_diagnostic_protocol',         2, 'AN' ), # 44
    array ( 'ntgsmr02_diagnostic_error_code',       3, 'AN' ), # 45
    array ( 'ntgsmr02_msc_number_npi_ei',           1, 'AN' ), # 46
    array ( 'ntgsmr02_msc_number_npi_noai',         1, 'AN' ), # 47
    array ( 'ntgsmr02_msc_number_npi',              2, 'AN' ), # 48
    array ( 'ntgsmr02_msc_number_string',          21, 'AN' ), # 49
    array ( 'ntgsmr02_record_number',              11, 'AN' ), # 50
    array ( 'ntgsmr02_m03_cell_sac_id',             5, 'AN' ), # 51  
    array ( 'ntgsmr02_m04_cell_sac_id',             5, 'AN' ), # 52
    array ( 'ntgsmr02_m05_supp_service_code_hot',   1, 'AN' ), # 53
    array ( 'ntgsmr02_m05_supp_service_code_group', 1, 'AN' ), # 54
    array ( 'ntgsmr02_m05_supp_service_code_type',  1, 'AN' ), # 55
    array ( 'ntgsmr02_m05_supp_service_parameters',31, 'AN' ), # 56
    array ( 'ntgsmr02_m18_service_key',            11, 'AN' ), # 57
    array ( 'ntgsmr02_m18_destination_routing_address', 31, 'AN' ), # 58
    array ( 'ntgsmr02_m18_service_control_point_address', 21, 'AN' ), # 59
    array ( 'ntgsmr02_m20_original_calling_number_string', 31, 'AN' ), # 60
    array ( 'ntgsmr02_rerout',                      1, 'AN' ), # 61
    array ( 'ntgsmr02_serv_feat',                   2, 'AN' ), # 62
    array ( 'ntgsmr02_caller_id_a',                 1, 'AN' ), # 63
    array ( 'ntgsmr02_caller_id_b',                 1, 'AN' ), # 64
    array ( 'ntgsmr02_market',                      3, 'AN' ), # 65
    array ( 'ntgsmr02_incoming_rte',               10, 'AN' ), # 66
    array ( 'ntgsmr02_outgoing_rte',               10, 'AN' ), # 67
    array ( 'ntgsmr02_a_bill_ind',                  1, 'AN' ), # 68
    array ( 'ntgsmr02_b_bill_ind',                  1, 'AN' ), # 69
    array ( 'ntgsmr02_incomplete',                  1, 'AN' ), # 70
    array ( 'ntgsmr02_forw_roam_flag',              1, 'AN' ), # 71
    array ( 'ntgsmr02_call_duration',               8, 'AN' ), # 72
    array ( 'ntgsmr02_ring_duration',               8, 'AN' ), # 73
    array ( 'ntgsmr02_rej_code',                    1, 'AN' ), # 74
    array ( 'ntgsmr02_a_sub_imsi',                 15, 'AN' ), # 75
    array ( 'ntgsmr02_a_sub_mdn',                  16, 'AN' ), # 76
    array ( 'ntgsmr02_b_sub_num',                  32, 'AN' ), # 77
    array ( 'ntgsmr02_b_sub_imsi',                 15, 'AN' ), # 78
    array ( 'ntgsmr02_b_sub_mdn',                  16, 'AN' ), # 79
    array ( 'ntgsmr02_num_translate',               1, 'AN' ), # 80
    array ( 'ntgsmr02_bill_c',                      1, 'AN' ), # 81
    array ( 'ntgsmr02_a_chg',                       1, 'AN' ), # 82
    array ( 'ntgsmr02_b_chg',                       1, 'AN' ), # 83
    array ( 'ntgsmr02_dialed_work',                32, 'AN' ), # 84
    array ( 'ntgsmr02_imei',                       21, 'AN' ), # 85
    array ( 'ntgsmr02_cause_for_termination',       3, 'AN' ), # 86
    array ( 'ntgsmr02_call_placed_date',            8, 'AN' ), # 87
    array ( 'ntgsmr02_call_placed_time',            6, 'AN' ), # 88
    array ( 'ntgsmr02_call_identifier_block',      10, 'AN' ), # 89
    array ( 'ntgsmr02_call_identifier_rcd_cnt',     5, 'AN' )  # 90
          );    

# added                                                     # 07-10-06
$ntgsmStructR03 = 
    array (
    array ( 'ntgsmr03_hexadecimal_id',              2, 'AN' ), # 0
    array ( 'ntgsmr03_release_release',             2, 'AN' ), # 1
    array ( 'ntgsmr03_release_version',             2, 'AN' ), # 2
    array ( 'ntgsmr03_structure_code_module_ind',   1, 'AN' ), # 3
    array ( 'ntgsmr03_structure_code',              4, 'AN' ), # 4
    array ( 'ntgsmr03_call_type_code',              3, 'AN' ), # 5
    array ( 'ntgsmr03_study_ind',                   1, 'AN' ), # 6
    array ( 'ntgsmr03_call_forward_ind',            1, 'AN' ), # 7
    array ( 'ntgsmr03_called_party_nt',             1, 'AN' ), # 8
    array ( 'ntgsmr03_called_party_npi_ei',         1, 'AN' ), # 9
    array ( 'ntgsmr03_called_party_npi_noai',       1, 'AN' ), # 10
    array ( 'ntgsmr03_called_party_npi',            2, 'AN' ), # 11
    array ( 'ntgsmr03_called_party_string',        21, 'AN' ), # 12
    array ( 'ntgsmr03_calling_number_nt',           1, 'AN' ), # 13
    array ( 'ntgsmr03_calling_number_npi_ei',       1, 'AN' ), # 14
    array ( 'ntgsmr03_calling_number_npi_noai',     1, 'AN' ), # 15
    array ( 'ntgsmr03_calling_number_npi',          2, 'AN' ), # 16
    array ( 'ntgsmr03_calling_number_string',      21, 'AN' ), # 17
    array ( 'ntgsmr03_called_number_nt',            1, 'AN' ), # 18
    array ( 'ntgsmr03_called_number_npi_ei',        1, 'AN' ), # 19
    array ( 'ntgsmr03_called_number_npi_noai',      1, 'AN' ), # 20
    array ( 'ntgsmr03_called_number_npi',           2, 'AN' ), # 21
    array ( 'ntgsmr03_called_number_string',       31, 'AN' ), # 22
    array ( 'ntgsmr03_called_equipment',           21, 'AN' ), # 23
    array ( 'ntgsmr03_additional_information',      1, 'AN' ), # 24
    array ( 'ntgsmr03_channel_allocation_time',    16, 'AN' ), # 25
    array ( 'ntgsmr03_answer_time',                16, 'AN' ), # 26
    array ( 'ntgsmr03_disconnect_time',            16, 'AN' ), # 27
    array ( 'ntgsmr03_release_time',               16, 'AN' ), # 28
    array ( 'ntgsmr03_off_air_call_setup',          1, 'AN' ), # 29
    array ( 'ntgsmr03_half_rate_in_use',            1, 'AN' ), # 30
    array ( 'ntgsmr03_cause_for_termination',       2, 'AN' ), # 31
    array ( 'ntgsmr03_call_reference',              7, 'AN' ), # 32
    array ( 'ntgsmr03_ms_classmark',               15, 'AN' ), # 33
    array ( 'ntgsmr03_classmark_timestamp',        16, 'AN' ), # 34
    array ( 'ntgsmr03_incoming_trunk_group',        5, 'AN' ), # 35
    array ( 'ntgsmr03_incoming_trunk_member',       5, 'AN' ), # 36
    array ( 'ntgsmr03_incoming_route_group',        3, 'AN' ), # 37
    array ( 'ntgsmr03_incoming_trunk_seizure',     16, 'AN' ), # 38
    array ( 'ntgsmr03_called_subscriber_category',  3, 'AN' ), # 39
    array ( 'ntgsmr03_call_ind',                    1, 'AN' ), # 40
    array ( 'ntgsmr03_call_duration',               7, 'AN' ), # 41
    array ( 'ntgsmr03_diagnostic_protocol',         2, 'AN' ), # 42
    array ( 'ntgsmr03_diagnostic_error_code',       3, 'AN' ), # 43
    array ( 'ntgsmr03_msc_number_npi_ei',           1, 'AN' ), # 44
    array ( 'ntgsmr03_msc_number_npi_noai',         1, 'AN' ), # 45
    array ( 'ntgsmr03_msc_number_npi',              2, 'AN' ), # 46
    array ( 'ntgsmr03_ msc_number_string',         21, 'AN' ), # 47
    array ( 'ntgsmr03_record_number',              11, 'AN' ), # 48
    array ( 'ntgsmr03_filler',                     32, 'AN' ), # 49
    array ( 'ntgsmr03_m03_cell_sac_id',             5, 'AN' ), # 50  
    array ( 'ntgsmr03_m04_cell_sac_id',             5, 'AN' ), # 51
    array ( 'ntgsmr03_m05_supp_service_code_hot',   1, 'AN' ), # 52
    array ( 'ntgsmr03_m05_supp_service_code_group', 1, 'AN' ), # 53
    array ( 'ntgsmr03_m05_supp_service_code_type',  1, 'AN' ), # 54
    array ( 'ntgsmr03_m05_supp_service_parameters',31, 'AN' ), # 55
    array ( 'ntgsmr03_m18_service_key',            11, 'AN' ), # 56
    array ( 'ntgsmr03_m18_destination_routing_address', 31, 'AN' ), # 57
    array ( 'ntgsmr03_m18_service_control_point_address', 21, 'AN' ), # 58
    array ( 'ntgsmr03_m20_original_calling_number_string', 31, 'AN' ), # 59
    array ( 'ntgsmr03_rerout',                      1, 'AN' ), # 60
    array ( 'ntgsmr03_serv_feat',                   2, 'AN' ), # 61
    array ( 'ntgsmr03_caller_id_a',                 1, 'AN' ), # 62
    array ( 'ntgsmr03_caller_id_b',                 1, 'AN' ), # 63
    array ( 'ntgsmr03_market',                      3, 'AN' ), # 64
    array ( 'ntgsmr03_incoming_rte',               10, 'AN' ), # 65
    array ( 'ntgsmr03_outgoing_rte',               10, 'AN' ), # 66
    array ( 'ntgsmr03_a_bill_ind',                  1, 'AN' ), # 67
    array ( 'ntgsmr03_b_bill_ind',                  1, 'AN' ), # 68
    array ( 'ntgsmr03_incomplete',                  1, 'AN' ), # 69
    array ( 'ntgsmr03_forw_roam_flag',              1, 'AN' ), # 70
    array ( 'ntgsmr03_call_duration',               8, 'AN' ), # 71
    array ( 'ntgsmr03_ring_duration',               8, 'AN' ), # 72
    array ( 'ntgsmr03_rej_code',                    1, 'AN' ), # 73
    array ( 'ntgsmr03_a_sub_imsi',                 15, 'AN' ), # 74
    array ( 'ntgsmr03_a_sub_mdn',                  16, 'AN' ), # 75
    array ( 'ntgsmr03_b_sub_num',                  32, 'AN' ), # 76
    array ( 'ntgsmr03_b_sub_imsi',                 15, 'AN' ), # 77
    array ( 'ntgsmr03_b_sub_mdn',                  16, 'AN' ), # 78
    array ( 'ntgsmr03_num_translate',               1, 'AN' ), # 79
    array ( 'ntgsmr03_bill_c',                      1, 'AN' ), # 80
    array ( 'ntgsmr03_a_chg',                       1, 'AN' ), # 81
    array ( 'ntgsmr03_b_chg',                       1, 'AN' ), # 82
    array ( 'ntgsmr03_dialed_work',                32, 'AN' ), # 83
    array ( 'ntgsmr03_imei',                       21, 'AN' ), # 84
    array ( 'ntgsmr03_cause_for_termination',       3, 'AN' ), # 85
    array ( 'ntgsmr03_call_placed_date',            8, 'AN' ), # 86
    array ( 'ntgsmr03_call_placed_time',            6, 'AN' ), # 87
    array ( 'ntgsmr03_call_identifier_block',      10, 'AN' ), # 88
    array ( 'ntgsmr03_call_identifier_rcd_cnt',     5, 'AN' )  # 89
          );    

# added                                                     # 07-10-06
$ntgsmStructR99 = 
    array (
    array ( 'ntgsmr99_hexadecimal_id', 2, 'AN' ),
    array ( 'ntgsmr99_release_release', 2, 'AN' ),
    array ( 'ntgsmr99_release_version', 2, 'AN' ),
    array ( 'ntgsmr99_structure_code_module_ind', 1, 'AN' ),
    array ( 'ntgsmr99_structure_code', 4, 'AN' )
          );    

# added                                                     # 07-11-06
$lucfmtrec_2329 = 
    array (
    array ( 'luc2329_rec_des_wrd',                         8, 'AN' ), # 0
    array ( 'luc2329_hex_id',                              2, 'AN' ), # 1
    array ( 'luc2329_structure_code_pfx',                  1, 'AN' ), # 2
    array ( 'luc2329_structure_code',                      4, 'AN' ), # 3
    array ( 'luc2329_call_type',                           3, 'AN' ), # 4
    array ( 'luc2329_sensor_type',                         3, 'AN' ), # 5
    array ( 'luc2329_sensor_id',                           7, 'AN' ), # 6
    array ( 'luc2329_rec_off_type',                        3, 'AN' ), # 7
    array ( 'luc2329_rec_off_id',                          7, 'AN' ), # 8
    array ( 'luc2329_time_ind',                            5, 'AN' ), # 9
    array ( 'luc2329_answer_status',                       1, 'AN' ), # 10
    array ( 'luc2329_origination_ind',                     7, 'AN' ), # 11
    array ( 'luc2329_srv_feature_ind',                     1, 'AN' ), # 12
    array ( 'luc2329_srv_feature',                         3, 'AN' ), # 13
    array ( 'luc2329_subscriber_number_type',              2, 'AN' ), # 14
    array ( 'luc2329_subscriber_number_digit_count',       2, 'AN' ), # 15
    array ( 'luc2329_subscriber_number_digits',           15, 'AN' ), # 16
    array ( 'luc2329_terminating_number_type',             2, 'AN' ), # 17
    array ( 'luc2329_terminating_number_digit_count',      2, 'AN' ), # 18
    array ( 'luc2329_terminating_number_digits',          15, 'AN' ), # 19
    array ( 'luc2329_billing_number_type',                 3, 'AN' ), # 20
    array ( 'luc2329_billing_number_digit_count',          2, 'AN' ), # 21
    array ( 'luc2329_billing_number_digits',              20, 'AN' ), # 22
    array ( 'luc2329_called_ans_date',                     9, 'AN' ), # 23
    array ( 'luc2329_called_ans_time',                     7, 'AN' ), # 24
    array ( 'luc2329_called_elp_time',                     9, 'AN' ), # 25
    array ( 'luc2329_trunk_num',                          11, 'AN' ), # 26
    array ( 'luc2329_msg_srv_ind',                         3, 'AN' ), # 27
    array ( 'luc2329_routing_tnn',                        11, 'AN' ), # 28
    array ( 'luc2329_roamer_ind',                          3, 'AN' ), # 29
    array ( 'luc2329_serial_num',                         11, 'AN' ), # 30
    array ( 'luc2329_srv_sys_id',                          5, 'AN' ), # 31
    array ( 'luc2329_voice_seizure_date',                  9, 'AN' ), # 32
    array ( 'luc2329_voice_seizure_time',                  7, 'AN' ), # 33
    array ( 'luc2329_voice_elapsed_time',                  9, 'AN' ), # 34
    array ( 'luc2329_init_cell_site',                      9, 'AN' ), # 35
    array ( 'luc2329_last_cell_site',                      9, 'AN' ), # 36
    array ( 'luc2329_num_handoffs',                        3, 'AN' ), # 37
    array ( 'luc2329_comm_fail_ind',                       7, 'AN' ), # 38
    array ( 'luc2329_type_tech',                           3, 'AN' ), # 39
    array ( 'luc2329_station_mark',                        3, 'AN' ), # 40
    array ( 'luc2329_calling_cat',                         5, 'AN' ), # 41
    array ( 'luc2329_filler',                             92, 'AN' ), # 42
    array ( 'luc2329_m329_outgoing_cpn_display_allowed',   1, 'AN' ), # 43
    array ( 'luc2329_m331_incoming_cpn_display_allowed',   1, 'AN' ), # 44
    array ( 'luc2329_m331_cpn_digit_count',                2, 'AN' ), # 45
    array ( 'luc2329_m331_cpn_digits',                    16, 'AN' ), # 46
    array ( 'luc2329_m620_service_feature_1',              3, 'AN' ), # 47
    array ( 'luc2329_m620_service_feature_2',              3, 'AN' ), # 48
    array ( 'luc2329_m620_service_feature_3',              3, 'AN' ), # 49
    array ( 'luc2329_m620_service_feature_4',              3, 'AN' ), # 50
    array ( 'luc2329_m620_service_feature_5',              3, 'AN' ), # 51
    array ( 'luc2329_m620_service_feature_6',              3, 'AN' ), # 52
    array ( 'luc2329_m621_originating_min_digit_count',    2, 'AN' ), # 53
    array ( 'luc2329_m621_originating_min_digits',        10, 'AN' ), # 54
    array ( 'luc2329_m621_data_digit_count',               2, 'AN' ), # 55
    array ( 'luc2329_m621_data_digits',                   10, 'AN' ), # 56
    array ( 'luc2329_m621_jip_digit_count',                2, 'AN' ), # 57
    array ( 'luc2329_m621_jip_digits',                     6, 'AN' ), # 58
    array ( 'luc2329_m621_originating_imsi_digit_count',   2, 'AN' ), # 59
    array ( 'luc2329_m621_originating_imsi_digits',       16, 'AN' ), # 60
    array ( 'luc2329_m621_forwarding_number_digit_count',  2, 'AN' ), # 61
    array ( 'luc2329_m621_forwarding_number_digits',      16, 'AN' ), # 62
    array ( 'luc2329_m621_dialed_digit_count',             2, 'AN' ), # 63
    array ( 'luc2329_m621_dialed_digits',                 21, 'AN' ), # 64
    array ( 'luc2329_m621_incoming_called_digit_count',    2, 'AN' ), # 65
    array ( 'luc2329_m621_incoming_called_digits',        16, 'AN' ), # 66
    array ( 'luc2329_m621_dcs_outpulsed_digit_count',      2, 'AN' ), # 67
    array ( 'luc2329_m621_dcs_outpulsed_digits',          30, 'AN' ), # 68
    array ( 'luc2329_m720_lrn_digit_count',                2, 'AN' ), # 69
    array ( 'luc2329_m720_lrn_digits',                    10, 'AN' ), # 70
    array ( 'luc2329_call_placed_date',                    8, 'AN' ), # 71
    array ( 'luc2329_call_placed_time',                    6, 'AN' ), # 72
    array ( 'luc2329_call_duration',                       9, 'AN' ), # 73
    array ( 'luc2329_call_duration_secs',                 10, 'AN' ), # 74
    array ( 'luc2329_call_placed_date_r',                  8, 'AN' ), # 75
    array ( 'luc2329_call_placed_time_r',                  6, 'AN' ), # 76
    array ( 'luc2329_call_duration_r',                     9, 'AN' ), # 77
    array ( 'luc2329_call_duration_secs_r',               10, 'AN' ), # 78
    array ( 'luc2329_originating_route',                  10, 'AN' ), # 79
    array ( 'luc2329_terminating_route',                  10, 'AN' ), # 80
    array ( 'luc2329_call_answer_status',                  1, 'AN' ), # 81
    array ( 'luc2329_carrier_prefix',                      5, 'AN' ), # 82
    array ( 'luc2329_serv_feat',                           2, 'AN' ), # 83
    array ( 'luc2329_b_sub_mdn_an',                       18, 'AN' ), # 84
    array ( 'luc2329_sub_mdn',                            19, 'AN' ), # 85
    array ( 'luc2329_sub_min_dig',                        15, 'AN' ), # 86
    array ( 'luc2329_call_date',                           8, 'AN' ), # 87
    array ( 'luc2329_call_time',                           8, 'AN' ), # 88
    array ( 'luc2329_call_time_r',                         8, 'AN' ), # 89
    array ( 'luc2329_des_mdn',                            19, 'AN' ), # 90
    array ( 'luc2329_dialed_mdn',                         19, 'AN' ), # 91
    array ( 'luc2329_mdn_nlz',                            15, 'AN' ), # 92
    array ( 'luc2329_lrn_digits',                         12, 'AN' ), # 93
    array ( 'luc2329_trunk_num',                          11, 'AN' ), # 94
    array ( 'luc2329_trunk_flag',                          1, 'AN' ), # 95
    array ( 'luc2329_i_flag',                              1, 'AN' ), # 96
    array ( 'luc2329_voice_flag',                          1, 'AN' ), # 97
    array ( 'luc2329_fax_digits',                         15, 'AN' ), # 98
    array ( 'luc2329_international_ind',                   1, 'AN' ), # 99
    array ( 'luc2329_bil_mdn',                            25, 'AN' ), # 100
    array ( 'luc2329_mdn_lz',                             15, 'AN' ), # 101
    array ( 'luc2329_cell_site',                           9, 'AN' ), # 102
    array ( 'luc2329_call_identifier_block',              10, 'AN' ), # 103
    array ( 'luc2329_call_identifier_rcd_cnt',             5, 'AN' ), # 104
    array ( 'luc2329_rerout',                              1, 'AN' ), # 105
    array ( 'luc2329_dir',                                 1, 'AN' ), # 106
    array ( 'luc2329_star_found_flag',                     1, 'AN' ), # 107
    array ( 'luc2329_prepay_flag',                         1, 'AN' ), # 108
    array ( 'luc2329_ntl_flw_me',                          2, 'AN' ), # 109
    array ( 'luc2329_billed_celltower',                   10, 'AN' ), # 110
    array ( 'luc2329_ld_route',                           10, 'AN' )  # 111
          );    

# added                                                     # 07-11-06
$lucfmtrec_2331 = 
    array (
    array ( 'luc2331_rec_des_wrd',                         8, 'AN' ), # 0
    array ( 'luc2331_hex_id',                              2, 'AN' ), # 1
    array ( 'luc2331_structure_code_pfx',                  1, 'AN' ), # 2
    array ( 'luc2331_structure_code',                      4, 'AN' ), # 3
    array ( 'luc2331_call_type',                           3, 'AN' ), # 4
    array ( 'luc2331_sensor_type',                         3, 'AN' ), # 5
    array ( 'luc2331_sensor_id',                           7, 'AN' ), # 6
    array ( 'luc2331_rec_off_type',                        3, 'AN' ), # 7
    array ( 'luc2331_rec_off_id',                          7, 'AN' ), # 8
    array ( 'luc2331_time_ind',                            5, 'AN' ), # 9
    array ( 'luc2331_answer_status',                       1, 'AN' ), # 10
    array ( 'luc2331_term_ind',                            3, 'AN' ), # 11
    array ( 'luc2331_srv_feature_ind',                     1, 'AN' ), # 12
    array ( 'luc2331_srv_feature',                         3, 'AN' ), # 13
    array ( 'luc2331_subscriber_number_type',              2, 'AN' ), # 14
    array ( 'luc2331_subscriber_number_digit_count',       2, 'AN' ), # 15
    array ( 'luc2331_subscriber_number_digits',           15, 'AN' ), # 16
    array ( 'luc2331_billing_number_type',                 3, 'AN' ), # 17
    array ( 'luc2331_billing_number_digit_count',          2, 'AN' ), # 18
    array ( 'luc2331_billing_number_digits',              20, 'AN' ), # 19
    array ( 'luc2331_trunk_num',                          11, 'AN' ), # 20
    array ( 'luc2331_ msg_srv_ind',                        3, 'AN' ), # 21
    array ( 'luc2331_routing_tnn',                        11, 'AN' ), # 22
    array ( 'luc2331_roamer_ind',                          3, 'AN' ), # 23
    array ( 'luc2331_serial_num',                         11, 'AN' ), # 24
    array ( 'luc2331_srv_sys_id',                          5, 'AN' ), # 25
    array ( 'luc2331_voice_seizure_date',                  9, 'AN' ), # 26
    array ( 'luc2331_voice_seizure_time',                  7, 'AN' ), # 27
    array ( 'luc2331_voice_elapsed_time',                  9, 'AN' ), # 28
    array ( 'luc2331_voice_answer_date',                   9, 'AN' ), # 29
    array ( 'luc2331_voice_answer_time',                   7, 'AN' ), # 30
    array ( 'luc2331_voice_ans_elp_time',                  9, 'AN' ), # 31
    array ( 'luc2331_init_cell_site',                      9, 'AN' ), # 32
    array ( 'luc2331_last_cell_site',                      9, 'AN' ), # 33
    array ( 'luc2331_num_handoffs',                        3, 'AN' ), # 34
    array ( 'luc2331_comm_fail_ind',                       7, 'AN' ), # 35
    array ( 'luc2331_type_tech',                           3, 'AN' ), # 36
    array ( 'luc2331_station_mark',                        3, 'AN' ), # 37
    array ( 'luc2331_calling_cat',                         5, 'AN' ), # 38
    array ( 'luc2331_filler',                            115, 'AN' ), # 39
    array ( 'luc2331_m329_outgoing_cpn_display_allowed',   1, 'AN' ), # 40
    array ( 'luc2331_m331_incoming_cpn_display_allowed',   1, 'AN' ), # 41
    array ( 'luc2331_m331_cpn_digit_count',                2, 'AN' ), # 42
    array ( 'luc2331_m331_cpn_digits',                    16, 'AN' ), # 43
    array ( 'luc2331_m620_service_feature_1',              3, 'AN' ), # 44
    array ( 'luc2331_m620_service_feature_2',              3, 'AN' ), # 45
    array ( 'luc2331_m620_service_feature_3',              3, 'AN' ), # 46
    array ( 'luc2331_m620_service_feature_4',              3, 'AN' ), # 47
    array ( 'luc2331_m620_service_feature_5',              3, 'AN' ), # 48
    array ( 'luc2331_m620_service_feature_6',              3, 'AN' ), # 49
    array ( 'luc2331_m621_originating_min_digit_count',    2, 'AN' ), # 50
    array ( 'luc2331_m621_originating_min_digits',        10, 'AN' ), # 51
    array ( 'luc2331_m621_data_digit_count',               2, 'AN' ), # 52
    array ( 'luc2331_m621_data_digits',                   10, 'AN' ), # 53
    array ( 'luc2331_m621_jip_digit_count',                2, 'AN' ), # 54
    array ( 'luc2331_m621_jip_digits',                     6, 'AN' ), # 55
    array ( 'luc2331_m621_originating_imsi_digit_count',   2, 'AN' ), # 56
    array ( 'luc2331_m621_originating_imsi_digits',       16, 'AN' ), # 57
    array ( 'luc2331_m621_forwarding_number_digit_count',  2, 'AN' ), # 58
    array ( 'luc2331_m621_forwarding_number_digits',      16, 'AN' ), # 59
    array ( 'luc2331_m621_dialed_digit_count',             2, 'AN' ), # 60
    array ( 'luc2331_m621_dialed_digits',                 21, 'AN' ), # 61
    array ( 'luc2331_m621_incoming_called_digit_count',    2, 'AN' ), # 62
    array ( 'luc2331_m621_incoming_called_digits',        16, 'AN' ), # 63
    array ( 'luc2331_m621_dcs_outpulsed_digit_count',      2, 'AN' ), # 64
    array ( 'luc2331_m621_dcs_outpulsed_digits',          30, 'AN' ), # 65
    array ( 'luc2331_m720_lrn_digit_count',                2, 'AN' ), # 66
    array ( 'luc2331_m720_lrn_digits',                    10, 'AN' ), # 67
    array ( 'luc2331_call_placed_date',                    8, 'AN' ), # 68
    array ( 'luc2331_call_placed_time',                    6, 'AN' ), # 69
    array ( 'luc2331_call_duration',                       9, 'AN' ), # 70
    array ( 'luc2331_call_duration_secs',                 10, 'AN' ), # 71
    array ( 'luc2331_call_placed_date_r',                  8, 'AN' ), # 72
    array ( 'luc2331_call_placed_time_r',                  6, 'AN' ), # 73
    array ( 'luc2331_call_duration_r',                     9, 'AN' ), # 74
    array ( 'luc2331_call_duration_secs_r',               10, 'AN' ), # 75
    array ( 'luc2331_originating_route',                  10, 'AN' ), # 76
    array ( 'luc2331_terminating_route',                  10, 'AN' ), # 77
    array ( 'luc2331_call_answer_status',                  1, 'AN' ), # 78
    array ( 'luc2331_carrier_prefix',                      5, 'AN' ), # 79
    array ( 'luc2331_serv_feat',                           2, 'AN' ), # 80
    array ( 'luc2331_b_sub_mdn_an',                       18, 'AN' ), # 81
    array ( 'luc2331_sub_mdn',                            19, 'AN' ), # 82
    array ( 'luc2331_sub_min_dig',                        15, 'AN' ), # 83
    array ( 'luc2331_call_date',                           8, 'AN' ), # 84
    array ( 'luc2331_call_time',                           8, 'AN' ), # 85
    array ( 'luc2331_call_time_r',                         8, 'AN' ), # 86
    array ( 'luc2331_des_mdn',                            19, 'AN' ), # 87
    array ( 'luc2331_dialed_mdn',                         19, 'AN' ), # 88
    array ( 'luc2331_mdn_nlz',                            15, 'AN' ), # 89
    array ( 'luc2331_lrn_digits',                         12, 'AN' ), # 90
    array ( 'luc2331_trunk_num',                          11, 'AN' ), # 91
    array ( 'luc2331_trunk_flag',                          1, 'AN' ), # 92
    array ( 'luc2331_i_flag',                              1, 'AN' ), # 93
    array ( 'luc2331_voice_flag',                          1, 'AN' ), # 94
    array ( 'luc2331_fax_digits',                         15, 'AN' ), # 95
    array ( 'luc2331_international_ind',                   1, 'AN' ), # 96
    array ( 'luc2331_bil_mdn',                            25, 'AN' ), # 97
    array ( 'luc2331_mdn_lz',                             15, 'AN' ), # 98
    array ( 'luc2331_cell_site',                           9, 'AN' ), # 99
    array ( 'luc2331_call_identifier_block',              10, 'AN' ), # 100
    array ( 'luc2331_call_identifier_rcd_cnt',             5, 'AN' ), # 101
    array ( 'luc2331_rerout',                              1, 'AN' ), # 102
    array ( 'luc2331_dir',                                 1, 'AN' ), # 103
    array ( 'luc2331_star_found_flag',                     1, 'AN' ), # 104
    array ( 'luc2331_prepay_flag',                         1, 'AN' ), # 105
    array ( 'luc2331_ntl_flw_me',                          2, 'AN' ), # 106
    array ( 'luc2331_billed_celltower',                   10, 'AN' ), # 107
    array ( 'luc2331_ld_route',                           10, 'AN' )  # 108
          );    

# added                                                     # 07-11-06
$lucfmtrec_9999 = 
    array (
    array ( 'luc9999_rec_des_wrd',                         8, 'AN' ), # 0
    array ( 'luc9999_hex_id',                              2, 'AN' ), # 1
    array ( 'luc9999_structure_code_pfx',                  1, 'AN' ), # 2
    array ( 'luc9999_structure_code',                      4, 'AN' ), # 3
    array ( 'luc9999_call_type',                           3, 'AN' )  # 4
          );    

# added                                                     # 07-12-06
$gprs_opn020 = 
    array (
    array ( 'gprs_record_type',                            2, 'AN' ), # 0
    array ( 'gprs_network_initiation',                     2, 'AN' ), # 1
    array ( 'gprs_system_type',                            6, 'AN' ), # 2
    array ( 'gprs_served_imsi',                           15, 'AN' ), # 3
    array ( 'gprs_served_imei',                           15, 'AN' ), # 4
    array ( 'gprs_served_msisdn',                         16, 'AN' ), # 5
    array ( 'gprs_sgsn_addr',                             16, 'AN' ), # 6
    array ( 'gprs_ms_network_capability',                 32, 'AN' ), # 7
    array ( 'gprs_routing_area',                          32, 'AN' ), # 8
    array ( 'gprs_location_area_code',                    32, 'AN' ), # 9
    array ( 'gprs_cell_id',                               15, 'AN' ), # 10
    array ( 'gprs_charging_id',                           15, 'AN' ), # 11
    array ( 'gprs_ggsn_address_used',                     32, 'AN' ), # 12
    array ( 'gprs_apn_network_id',                        20, 'AN' ), # 13
    array ( 'gprs_apn_selection_mode',                     3, 'AN' ), # 14
    array ( 'gprs_pdp_type',                              64, 'AN' ), # 15
    array ( 'gprs_served_pdp_addr',                       20, 'AN' ), # 16
    array ( 'gprs_start_time',                            19, 'AN' ), # 17
    array ( 'gprs_duration',                               8, 'AN' ), # 18
    array ( 'gprs_sgsn_change',                            8, 'AN' ), # 19
    array ( 'gprs_cause_for_closing',                      2, 'AN' ), # 20
    array ( 'gprs_diagnostics',                            6, 'AN' ), # 21
    array ( 'gprs_local_rec_seq_num',                     10, 'AN' ), # 22
    array ( 'gprs_node_id',                               20, 'AN' ), # 23
    array ( 'gprs_record_extensions',                      6, 'AN' ), # 24
    array ( 'gprs_seq_num',                               10, 'AN' ), # 25
    array ( 'gprs_apn_operator_id',                       20, 'AN' ), # 26
    array ( 'gprs_rnc_unsent_downlink_vol',               10, 'AN' ), # 27
    array ( 'gprs_camel',                                  6, 'AN' ), # 28
    array ( 'gprs_charging_characteristcs',                1, 'AN' ), # 29
    array ( 'gprs_qos_requested_peak_throughput',          1, 'AN' ), # 30
    array ( 'gprs_qos_requested_precedence',               1, 'AN' ), # 31
    array ( 'gprs_qos_requested_reliability',              1, 'AN' ), # 32
    array ( 'gprs_qos_requested_mean_throughput',          2, 'AN' ), # 33
    array ( 'gprs_qos_requested_delay',                    1, 'AN' ), # 34
    array ( 'gprs_data_volume_uplink',                    50, 'AN' ), # 35
    array ( 'gprs_change_time',                           19, 'AN' ), # 36
    array ( 'gprs_qos_negotiated_peak_throughput',        50, 'AN' ), # 37
    array ( 'gprs_qos_negotiated_precedence',             50, 'AN' ), # 38
    array ( 'gprs_qos_negotiated_reliability',            50, 'AN' ), # 39
    array ( 'gprs_qos_negotiated_mean_throughput',        50, 'AN' ), # 40
    array ( 'gprs_qos_negotiated_delay',                  50, 'AN' ), # 41
    array ( 'gprs_data_volume_downlink',                  50, 'AN' ), # 42
    array ( 'gprs_change_condition',                      50, 'AN' ), # 43
    array ( 'gprs_site',                                  10, 'AN' ), # 44
    array ( 'gprs_file',                                 256, 'AN' ), # 45
    array ( 'gprs_dynamic_addr_flag',                      6, 'AN' ), # 46
    array ( 'gprs_blk_cnt',                               10, 'AN' ), # 47
    array ( 'gprs_rec_cnt',                                5, 'AN' )  # 48
          );    

function matchRecord( $fpSrc, $fpDst, $srcType, $dstType, $srcRecNum, $dstRecNum )
{
    global $structSize;

    #  Read a record from the source file
    #
    $srcRecStr = getRecord( $fpSrc, $srcType, $srcRecNum );

    #  Position the search record to 1 past
    #  the current record
    #
    $recNum = $dstRecNum + 1;

    #  Clear our done flag
    #
    $done = FALSE;

    #  While we haven't found a matched
    #  record, or exhausted our search
    #
    while ( !$done )
    {
        #  Read a record from the target file,
        #  and if we fail to read a record, and
        #  we are not at the beginning of the file,
        #  set our record number to the beginning and
        #  try again, otherwise return FALSE
        #  
        if ( !( $dstRecStr = getRecord( $fpDst, $dstType, $recNum ) ) )
        {
            if ( $recNum != 0 )
                $recNum = 0;
            else
                $done = TRUE;
        }

        #  If our records match, return the
        #  target record number
        #
        if ( isRecordMatch( $srcRecStr, $srcType, $dstRecStr, $dstType ) )
            return $recNum;
   
        #  If the current record number
        #  is equal to our starting record
        #  number, then exit this loop
        #
        if ( $recNum == $dstRecNum )
            $done = TRUE;

        #  Advance the record number
        #
        $recNum++;
    }

    return -1;
}


function isRecordMatch( $recStr1, $type1, $recStr2, $type2 )
{
    global $useCallDir;
    global $useMinMdn;

    #  Get the matching structures for each
    #  record
    #
    if ( 15 == $type1 )                                               # 07-10-06
    {                                                                 # 07-10-06
        $recStruct1 = getNorGsmStruct ( $recStr1 );                   # 07-10-06
    }                                                                 # 07-10-06
    else if ( 16 == $type1 )                                          # 07-11-06
    {                                                                 # 07-11-06
        $recStruct1 = getLucentStruct ( $recStr1 );                   # 07-11-06
    }                                                                 # 07-11-06
    else                                                              # 07-10-06
    {                                                                 # 07-10-06
        $recStruct1 = getRecStruct ( $type1 );                        # 07-10-06
    }                                                                 # 07-10-06
                                                                      # 07-10-06
    if ( 15 == $type2 )                                               # 07-10-06
    {                                                                 # 07-10-06
        $recStruct2 = getNorGsmStruct ( $recStr2 );                   # 07-10-06
    }                                                                 # 07-10-06
    else if (16 == $type2)                                            # 07-11-06
    {                                                                 # 07-11-06
        $recStruct2 = getLucentStruct ( $recStr2 );                   # 07-11-06
    }                                                                 # 07-11-06
    else                                                              # 07-10-06
    {                                                                 # 07-10-06
        $recStruct2 = getRecStruct( $type2 );                         # 07-10-06
    }                                                                 # 07-10-06

    #  Get the connection dates
    #
    $connDate1 = getConnectDate( $recStr1, $type1 );
    $connDate2 = getConnectDate( $recStr2, $type2 );

    #  Find the smallest of the 2 date strings
    #
    $minSize   = strlen( $connDate1 ) > strlen( $connDate2 ) ? 
                 strlen( $connDate2 ) : strlen( $connDate1 );

    #  Trim each date string down to the lowest size
    #
    $connDate1 = substr( $connDate1, 0, $minSize );
    $connDate2 = substr( $connDate2, 0, $minSize );

    #  If the connect times are different
    #
    if ( $connDate1 != $connDate2 )
        return FALSE;

    #  If we are supposed to use the Billed MIN
    #
    if ( $useMinMdn == 1 )
    {
        #  If the Billed Min Numbers are different
        #
        if ( getBilledMin( $recStr1, $type1 ) != getBilledMin( $recStr2, $type2 ) )
            return FALSE;
    }

    #  If we are supposed to use the Billed MDN
    #
    if ( $useMinMdn == 2 )
    {
        #  If the Billed Min Numbers are different
        #
        if ( getBilledMdn( $recStr1, $type1 ) != getBilledMdn( $recStr2, $type2 ) )
            return FALSE;
    }

    #  If we are supposed to use the Billed IMSI
    #
    if ( $useMinMdn == 3 )
    {
        #  If the Billed IMSI Numbers are different
        #
        if ( getBilledImsi( $recStr1, $type1 ) != getBilledImsi( $recStr2, $type2 ) )
            return FALSE;
    }

    #  If we are supposed to use the call
    #  direction to determine record equivelance,
    #
    if ( $useCallDir )
    {
        #  If the Call Direction is different
        #
        if ( getCallDirection( $recStr1, $type1 ) != getCallDirection( $recStr2, $type2 ) )
            return FALSE;
    }

    #  The Records Match
    #
    return TRUE;
}


function getConnectDate( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 34 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 56 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 70 );
        break;

    case 3:  //  CALCDR
        $fieldVal = sprintf( "%04d%02d%02d%02d%02d%02d",
                             intval( getFieldVal( $recStr, $type, 3 ) ) + 1900,
                             intval( getFieldVal( $recStr, $type, 4 ) ),
                             intVal( getFieldVal( $recStr, $type, 5 ) ),
                             intVal( getFieldVal( $recStr, $type, 6 ) ),
                             intVal( getFieldVal( $recStr, $type, 7 ) ),
                             intVal( getFieldVal( $recStr, $type, 8 ) ) );
        break;

    case 10: //  CALCDR2
        $fieldVal = sprintf( "%04d%02d%02d%02d%02d%02d",
                             intval( getFieldVal( $recStr, $type, 3 ) ) + 1900,
                             intval( getFieldVal( $recStr, $type, 4 ) ),
                             intVal( getFieldVal( $recStr, $type, 5 ) ),
                             intVal( getFieldVal( $recStr, $type, 6 ) ),
                             intVal( getFieldVal( $recStr, $type, 7 ) ),
                             intVal( getFieldVal( $recStr, $type, 8 ) ) );
        break;

    case 4:  //  DATCDR
        $fieldVal = sprintf( "%04d%02d%02d%02d%02d%02d",
                             intval( getFieldVal( $recStr, $type, 3 ) ) + 1900,
                             intval( getFieldVal( $recStr, $type, 4 ) ),
                             intVal( getFieldVal( $recStr, $type, 5 ) ),
                             intVal( getFieldVal( $recStr, $type, 6 ) ),
                             intVal( getFieldVal( $recStr, $type, 7 ) ),
                             intVal( getFieldVal( $recStr, $type, 8 ) ) );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 3 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 56 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 70 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 49 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 88 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 43 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 101 ) . getFieldVal( $recStr, $type, 79 );
        break;

    case 15:  // Nortel GSM                                           # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 26 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 26 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 23 );            # 07-11-06
            $fieldVal = substr($fieldVal, 1, 8);                      # 07-11-06
            $fieldVal .= getFieldVal ($recStr, $type, 24);            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 29 );            # 07-11-06
            $fieldVal = substr($fieldVal, 1, 8);                      # 07-11-06
            $fieldVal .= getFieldVal ($recStr, $type, 30);            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = getFieldVal( $recStr, $type, 17 );                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getBilledMin( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 14 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 32 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 10 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 10 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 11 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 11 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 32 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 21 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 32 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 17 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 116 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 17 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getBilledMdn( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 13 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 31 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 35 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 1 );
        break;

    case 10:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 1 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 1 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 1 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 31 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 35 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 18 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 31 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 14 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 117 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 17 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 19 );            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = getFieldVal( $recStr, $type, 5 );                 # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getBilledImsi( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 12 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 30 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 34 );
        break;

    case 3:  //  CALCDR
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 21, 7 ) );
        break;

    case 10:  //  CALCDR2
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 21, 7 ) );
        break;

    case 4:  //  DATCDR
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 21, 7 ) );
        break;

    case 5:  //  SMSCDR
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 21, 7 ) );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 30 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 34 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 17 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 30 );
        break;

    case 11:  //  Cingular Fixed Length
        $fieldVal = getFieldVal( $recStr, $type, 21 );
        break;

    case 12:  //  Ciber Type 22
        $fieldVal = getFieldVal( $recStr, $type, 6  );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 13 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 117 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 12 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 12 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}

function getBilledMDN15( $recStr, $type )
{
    $fieldVal = '';

    $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 1, 7 ) );

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}

function getDesImsi( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 44 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 70 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 84 );
        break;

    case 3:  //  CALCDR
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 74, 7 ) );
        break;

    case 10:  //  CALCDR2
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 74, 7 ) );
        break;

    case 4:  //  DATCDR
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 74, 7 ) );
        break;

    case 5:  //  SMSCDR
        $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 74, 7 ) );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 70 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 84 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 60 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 30 );
        break;

    case 11:  //  Cingular Fixed Length
        $fieldVal = getFieldVal( $recStr, $type, 21 );
        break;

    case 12:  //  Ciber Type 22
        $fieldVal = getFieldVal( $recStr, $type, 6  );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 54 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 120 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 12 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getDialedNumber( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 43 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 68 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 82 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 16 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 16 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 17 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 17 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 68 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 82 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 59 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 67 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 53 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 17 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 36 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 19 );            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 19 );            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getCallerID( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 20 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 47 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 53 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 54 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 52 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 52 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 47 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 22 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 18 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 17 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 17 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getCallerID15( $recStr, $type ) # jrb 05-16-06
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 20 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 47 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 53 );
        break;

    case 10:  //  CALCDR2
        # $fieldVal = getFieldVal( $recStr, $type, 54 );
        $fieldVal = convertFieldToVal ('BI', 7, substr ($recStr, 145, 7 ) );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 52 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 52 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 47 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 22 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 18 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 17 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 17 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getBilledCellTower( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 15 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 33 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 30 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 31 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 32 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 31 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 31 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 33 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 30 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 23 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 33 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 31 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 107 );
        break;

    case 15: // nortel gsm                                            # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 66 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 65 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 110 );           # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 107 );           # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = getFieldVal( $recStr, $type, 6 );                 # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getLDRoute( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 66 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 95 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 110 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 45 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 46 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 44 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 44 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 95 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 110 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 82 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 95 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 25 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 108 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 67 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 66 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 111 );           # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 108 );           # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = getFieldVal( $recStr, $type, 6 );                 # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getIncompleteFlag( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 16 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 34 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 43 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 50 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 51 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 49 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 49 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 34 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 43 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 24 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 34 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 20 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 111 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 31 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 31 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal (incompelte) = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getNoAirFlag( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 72 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 101 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 117 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 29 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 30 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 29 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 29 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 101 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 117 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 89 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 99 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 82 );
        break;

    case 14:  //  ntstruct
        #$fieldVal = getFieldVal( $recStr, $type, 111 );
        $fieldVal = '';
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 43 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 41 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal (no_air) = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getNoTollFlag( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 113 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 169 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 192 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 10:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 29 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 169 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 192 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 148 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 164 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 142 );
        break;

    case 14:  //  ntstruct
        #$fieldVal = getFieldVal( $recStr, $type, 111 );
        $fieldVal = '';
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal (zero_ld) = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getDestMDN( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 46 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 72 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 86 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 18 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 19 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 19 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 19 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 72 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 86 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 62 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 71 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 56 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 120 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 19 );            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = getFieldVal( $recStr, $type, 16 );            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = getFieldVal( $recStr, $type, 5 );                 # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getDestMIN( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 47 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 73 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 87 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 20 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 21 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 21 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 21 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 73 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 87 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 63 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 72 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 57 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 119 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 22 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getDesMDN15( $recStr, $type )
{
    $fieldVal = '';

    $fieldVal = convertFieldToVal( 'BI', 7, substr( $recStr, 67, 7 ) );

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}

function getHomeSID( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 60 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 89 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 104 );
        break;

    case 3:   //  CALCDR
    case 10:  //  CALCDR2
    case 4:   //  DATCDR
    case 5:   //  SMSCDR
        $fieldVal = 'ivan';
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 89 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 104 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 76 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 87 );
        break;

    case 12:  //  Ciber Type 22
        $fieldVal = getFieldVal( $recStr, $type, 4 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 70 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 74 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getServSID( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 80 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 126 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 148 );
        break;

    case 3:   //  CALCDR
    case 10:  //  CALCDR2
    case 4:   //  DATCDR
    case 5:   //  SMSCDR
        $fieldVal = 'ivan';
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 126 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 148 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 113 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 121 );
        break;

    case 12:  //  Ciber Type 22
        $fieldVal = getFieldVal( $recStr, $type, 11 );
        break;

    case 13:  //  udrsd (Wherify)
        $fieldVal = getFieldVal( $recStr, $type, 107 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 76 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getCallDirection( $recStr, $type )
{
    #  If we are not OldGen, get the call type of
    #  the record
    #
    switch ( $type )
    {
    case 0:  //  udrsp ver1.0
    case 1:  //  udr ver1.0 (pipe)
    case 2:  //  udr ver2.0 (pipe)
    case 6:  //  udr ver1.0
    case 7:  //  udr ver2.0
    case 8:  //  udrsp ver2.0
    case 9:  //  udrch (pipe)
    case 13: //  udrsd (Wherify)

        #  Get the call type field
        #
        $fieldVal = getCallTypeID( $recStr, $type );
 
        #  Extract the Direction from the type
        #
        $fieldVal = substr( $fieldVal, 3, 2 );
        break;

    case 3:  //  CALCDR
        #  Get the Direction string
        #
        $fieldVal = getFieldVal( $recStr, $type, 23 );

        #  Get the Origination Indicator
        #
        $origInd  = getFieldVal( $recStr, $type, 48 );
        break;

    case 10:  //  CALCDR2
        #  Get the Direction string
        #
        $fieldVal = getFieldVal( $recStr, $type, 24 );

        #  Get the Origination Indicator
        #
        $origInd  = getFieldVal( $recStr, $type, 49 );
        break;

    case 4:  //  DATCDR
        #  Get the Direction string
        #
        $fieldVal = getFieldVal( $recStr, $type, 24 );

        #  Get the Origination Indicator
        #
        $origInd  = getFieldVal( $recStr, $type, 49 );
        break;

    case 5:   //  SMSCDR
        #  Get the Direction string
        #
        $fieldVal = getFieldVal( $recStr, $type, 24 );

        #  Get the Origination Indicator
        #
        $origInd  = getFieldVal( $recStr, $type, 49 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 0 );
        break;
                                                                      # 07-10-06
    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = '1';                                          # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = '2';                                          # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = '0';                                          # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  In OldGen, convert string directions to
    #  numbers
    #
    if ( $type == 3 || $type == 4 || $type == 5 || $type == 10 )
    {
        switch ( $fieldVal )
        {
        case 'MO':
            $fieldVal = '00';
            break;
    
        case 'ML':
            $fieldVal = '01';
            break;
    
        case 'MT':
        case 'LM':
            $fieldVal = '02';
            break;
        
        case 'MM':
            #  If the origination indicator
            #  is 'T', then it is mobile-mobile
            #  terminated, otherwise it is
            #  mobile-mobile originated
            #
            if ( $origInd == 'T' )
                $fieldVal = '09';
            else
                $fieldVal = '04';
            break;
       }

       #  Get the service feature value
       #
       $servFeat = getServiceFeature( $recStr, $type );

       #  Depeinding on the service feature,
       #  change the call direction
       # 
       if ( intval( $servFeat ) )
       {
           #  If the call was originated,
           #  then make it Feature-Orig,
           #  otherwise, Feature-Term
           #
           if ( $fieldVal == '04' || $fieldVal == '01' )
           {
               #  Feature-Orig
               #
               $fieldVal = '05';
           }
           else if ( $fieldVal == '09' || $fieldVal == '02' )
           {
               #  Feature-Term
               #
               $fieldVal = '06';
           }
       }
    }

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getHomeRoam( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
    case 1:  //  udr ver1.0 (pipe)
    case 2:  //  udr ver2.0 (pipe)
    case 6:  //  udr ver1.0
    case 7:  //  udr ver2.0
    case 8:  //  udrsp ver2.0
    case 9:  //  udrch (pipe)
    case 13: //  udrsd (Wherify)

        #  Get the call type field
        #
        $fieldVal = getCallTypeID( $recStr, $type );

        #  Extract the Home/Roam Value from the type
        #
        $fieldVal = substr( $fieldVal, 2, 1 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 14 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 14 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 15 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 15 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 9 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getCallDurationSec( $recStr, $type )
{
    $fieldVal = '';
    $seconds  = 0.0;

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal= getFieldVal( $recStr, $type, 51 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 78 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 92 );
        break;

    case 3:  //  CALCDR
        $fieldVal= intval( getFieldVal( $recStr, $type, 25 ) * 60 ) + 
                   intval( getFieldVal( $recStr, $type, 26 ) );
        break;

    case 10:  //  CALCDR2
        $fieldVal= intval( getFieldVal( $recStr, $type, 26 ) * 60 ) + 
                   intval( getFieldVal( $recStr, $type, 27 ) );
        break;

    case 4:  //  DATCDR
        $fieldVal = 0;
        break;

    case 5:  //  SMSCDR
        $fieldVal = 0;
        break;

    case 6:  //  udr ver1.0
        $fieldVal= getFieldVal( $recStr, $type, 78 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal= getFieldVal( $recStr, $type, 92 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal= getFieldVal( $recStr, $type, 65 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 75 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 113 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 43 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 41 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-11-06
        $fieldVal = 0;                                                # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #  If the type is one that has the 'mmmmsst' format,
    #  perform the calculation
    #
    if ( $type != 3 && $type != 4 && $type != 5 && $type != 10 )
    {
        $seconds = floor( $fieldVal / 1000 ) * 60.0 +
                        ( $fieldVal % 1000 ) / 10.0;
    }
    else
    {
        $seconds = $fieldVal;
    }

    #  Return the field value to
    #  the caller
    #
    return $seconds;
}


function getRingDurationSec( $recStr, $type )
{
    $fieldVal = '';
    $seconds  = 0.0;

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal= getFieldVal( $recStr, $type, 50 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 77 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 93 );
        break;

    case 3:  //  CALCDR
        $fieldVal = intval( getFieldVal( $recStr, $type, 39 ) ) * 60 + 
                    intval( getFieldVal( $recStr, $type, 40 ) );
        break;

    case 10:  //  CALCDR2
        $fieldVal = intval( getFieldVal( $recStr, $type, 40 ) ) * 60 + 
                    intval( getFieldVal( $recStr, $type, 41 ) );
        break;

    case 4:  //  DATCDR
        $fieldVal = 0;
        break;

    case 5:  //  SMSCDR
        $fieldVal = 0;
        break;

    case 6:  //  udr ver1.0
        $fieldVal= getFieldVal( $recStr, $type, 77 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal= getFieldVal( $recStr, $type, 93 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal= getFieldVal( $recStr, $type, 66 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 76 );
        break;

    case 11:  //  Cingular Fixed Length
        $fieldVal= getFieldVal( $recStr, $type, 24 );
        break;

    case 12:  //  Ciber Type 22
        $fieldVal= getFieldVal( $recStr, $type, 37 );
        break;

    case 13:  //  udrsd (Wherify)         
        $fieldVal= getFieldVal( $recStr, $type, 60 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 114 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 43 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 41 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #  If the type is one that has the 'mmmmsst' format,
    #  perform the calculation
    #
    if ( $type != 3 && $type != 4 && $type != 5 && $type != 10 )
    {
        $seconds = floor( $fieldVal / 1000 ) * 60.0 +
                        ( $fieldVal % 1000 ) / 10.0;
    }
    else
    {
        $seconds = $fieldVal;
    }

    #  Return the field value to
    #  the caller
    #
    return $seconds;
}


function getBilledESN( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 10 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 32 );
        break;

    case 3:  //  CALCDR
        $fieldVal = convertFieldToVal( 'BI', 5, substr( $recStr, 14, 5 ) );
        break;

    case 10:  //  CALCDR2
        $fieldVal = convertFieldToVal( 'BI', 5, substr( $recStr, 14, 5 ) );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 9 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 9 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 32 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 15 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 28 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 38 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 23 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 23 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getBilledIMEI( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 11 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 29 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 33 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 9 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 9 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 9 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 9 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 29 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 33 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 16 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 29 );
        break;

    case 14:  //  ntstruct
        #$fieldVal = getFieldVal( $recStr, $type, 38 );
        $fieldVal = '';
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 23 );            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = getFieldVal( $recStr, $type, 23 );            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getServiceFeature( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 82 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 128 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 149 );
        break;

    case 3:  //  CALCDR
        $fieldVal = getFieldVal( $recStr, $type, 24 );
        break;

    case 10:  //  CALCDR2
        $fieldVal = getFieldVal( $recStr, $type, 25 );
        break;

    case 4:  //  DATCDR
        $fieldVal = getFieldVal( $recStr, $type, 25 );
        break;

    case 5:  //  SMSCDR
        $fieldVal = getFieldVal( $recStr, $type, 25 );
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 128 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 149 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 114 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal= getFieldVal( $recStr, $type, 123 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 103 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getCallTypeID( $recStr, $type )
{
    $fieldVal = '';

    #  Depending on the type, extract
    #  a field from the record
    #
    switch( $type )
    {
    case 0:  //  udrsp
        $fieldVal = getFieldVal( $recStr, $type, 0 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 11 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 14 );
        break;

    case 3:  //  CALCDR
        break;

    case 10:  //  CALCDR
        break;

    case 4:  //  DATCDR
        break;

    case 5:  //  SMSCDR
        break;

    case 6:  //  udr ver1.0
        $fieldVal = getFieldVal( $recStr, $type, 11 );
        break;

    case 7:  //  udr ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 14 );
        break;

    case 8:  //  udrsp ver2.0
        $fieldVal = getFieldVal( $recStr, $type, 0 );
        break;

    case 9:  //  udrch (pipe)
        $fieldVal = getFieldVal( $recStr, $type, 11 );
        break;

    case 14:  //  ntstruct
        $fieldVal = getFieldVal( $recStr, $type, 28 );
        break;

    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $fieldVal = 0;                                            # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $fieldVal = 0;                                            # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $fieldVal = 0;                                                # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        $fieldVal = 'ivan';
        break;
    }

    #echo "fieldVal = '$fieldVal'", "<br>\n";

    #  Return the field value to
    #  the caller
    #
    return $fieldVal;
}


function getXNumber( $recStr, $type )
{
    #  If we are not OldGen, get the call type of
    #  the record
    #
    switch ( $type )
    {
    case 0:  //  udrsp ver1.0 
        $blkCnt = getFieldVal( $recStr, $type, 17 );
        $recCnt = getFieldVal( $recStr, $type, 18 );
        break;

    case 1:  //  udr ver1.0 (pipe)
        $blkCnt = getFieldVal( $recStr, $type, 35 );
        $recCnt = getFieldVal( $recStr, $type, 36 );
        break;

    case 2:  //  udr ver2.0 (pipe)
        $blkCnt = getFieldVal( $recStr, $type, 44 );
        $recCnt = getFieldVal( $recStr, $type, 45 );
        break;

    case 6:  //  udr ver1.0
        $blkCnt = getFieldVal( $recStr, $type, 35 );
        $recCnt = getFieldVal( $recStr, $type, 36 );
        break;

    case 7:  //  udr ver2.0
        $blkCnt = getFieldVal( $recStr, $type, 44 );
        $recCnt = getFieldVal( $recStr, $type, 45 );
        break;

    case 8:  //  udrsp ver2.0
        $blkCnt = getFieldVal( $recStr, $type, 25 );
        $recCnt = getFieldVal( $recStr, $type, 26 );
        break;

    case 9:  //  udrch (pipe)
        $blkCnt = getFieldVal( $recStr, $type, 35 );
        $recCnt = getFieldVal( $recStr, $type, 36 );
        break;

    case 3:  //  CALCDR
        $blkCnt = 0;
        $recCnt = getFieldVal( $recStr, $type, 30 );
        break;

    case 10:  //  CALCDR2
        $blkCnt = 0;
        $recCnt = getFieldVal( $recStr, $type, 31 );
        break;

    case 4:  //  DATCDR
        $blkCnt = 0;
        $recCnt = getFieldVal( $recStr, $type, 30 );
        break;

    case 5:   //  SMSCDR
        $blkCnt = 0;
        $recCnt = getFieldVal( $recStr, $type, 30 );
        break;

    case 14:  //  ntstruct
        $blkCnt = getFieldVal( $recStr, $type, 126 );
        $recCnt = getFieldVal( $recStr, $type, 127 );
        break;
                                                                      # 07-10-06
    case 15:                                                          # 07-10-06
        $strCode = getNorGsmStrCode( $recStr );                       # 07-10-06
        if (2 == $strCode)                                            # 07-10-06
        {                                                             # 07-10-06
            $blkCnt = 0;                                              # 07-10-06
            $recCnt = 0;                                              # 07-10-06
        }                                                             # 07-10-06
        else if (3 == $strCode)                                       # 07-10-06
        {                                                             # 07-10-06
            $blkCnt = 0;                                              # 07-10-06
            $recCnt = 0;                                              # 07-10-06
        }                                                             # 07-10-06
        else                                                          # 07-10-06
        {                                                             # 07-10-06
            $blkCnt = 0;                                              # 07-10-06
            $recCnt = 0;                                              # 07-10-06
        }                                                             # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  // Lucent                                               # 07-11-06
        $strCode = getLucentStrCode( $recStr );                       # 07-11-06
        if (2329 == $strCode)                                         # 07-11-06
        {                                                             # 07-11-06
            $blkCnt = 0;                                              # 07-11-06
            $recCnt = 0;                                              # 07-11-06
        }                                                             # 07-11-06
        else if (2331 == $strCode)                                    # 07-11-06
        {                                                             # 07-11-06
            $blkCnt = 0;                                              # 07-11-06
            $recCnt = 0;                                              # 07-11-06
        }                                                             # 07-11-06
        else                                                          # 07-11-06
        {                                                             # 07-11-06
            $blkCnt = 0;                                              # 07-11-06
            $recCnt = 0;                                              # 07-11-06
        }                                                             # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $blkCnt = 0;                                                  # 07-12-06
        $recCnt = 0;                                                  # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    }

    #  Return the field value to
    #  the caller
    #
    return array( $blkCnt, $recCnt );
}



function getFieldVal( $recStr, $type, $fieldNo )
{
    #  Clear the script timeout value
    #
    set_time_limit( 30 );

    #  Retrieve the record structure
    #  for this record type
    #
    
    if (15 == $type)                                                  # 07-10-06
    {                                                                 # 07-10-06
        $recStruct = getNorGsmStruct( $recStr );                      # 07-10-06
    }                                                                 # 07-10-06
    else if (16 == $type)                                             # 07-11-06
    {                                                                 # 07-11-06
        $recStruct = getLucentStruct( $recStr );                      # 07-11-06
    }                                                                 # 07-11-06
    else                                                              # 07-10-06
    {                                                                 # 07-10-06
        $recStruct = getRecStruct( $type );                           # 07-10-06
    }                                                                 # 07-10-06

    #  Clear the field value
    #
    $fieldVal = '';

    #  Clear the starting position of the
    #  field in the record string
    #
    $startPos = 0;

    #  If the file record is of type
    #  UDR pipe, then it is in pipe,
    #  format and needs to be split
    #  at each pipe
    #
    if ( $type == 1 || $type == 2 || $type == 9 )
    {
        #  Break the input line at each
        #  pipe '|'
        #
        $recField = explode( '|', $recStr );

        #  If we are looking at a UDRCH structure
        #  then compensate for the leading '|'
        #  by removing it from the top of the
        #  array
        #
        if ( $type == 9 ) array_shift( $recField );

        #  Extract the column information
        #  form the the record structure array
        #
        $colInfo = $recStruct[ $fieldNo ];

        #  Get the field value from
        #  the exploded record
        #
        $fieldVal = $recField[ $fieldNo ];
    }
    else
    {
        #  Loop through the fields preceeding the
        #  field we wish to extract, and calculate
        #  the position in the record string where
        #  our field begins
        #
        for ( $i = 0; $i < $fieldNo; $i++ )
        {
            #  Extract the column information
            #  from the record structure array
            #
            $colInfo = $recStruct[ $i ];

            #  Advance the starting position
            #  of the columns
            #
            $startPos += intVal( $colInfo[ 1 ] );
        }

        #  Extract the column information
        #  for the specific column the caller
        #  requested
        #
        $colInfo = $recStruct[ $fieldNo ];

        #  Extract and convert the field value
        #  to a string
        #
        $fieldVal = substr( $recStr, $startPos, $colInfo[ 1 ] );
    }

    #echo "startPos = $startPos<br>\n";
    #echo "fieldVal (before) = $fieldVal<br>\n";
    #echo "colInfo = ", $colInfo[ 0 ], ", ", $colInfo[ 1 ], ", ", $colInfo[ 2 ], "<br>\n";

    #  Convert the value to a string
    #
    $fieldVal = convertFieldToVal( $colInfo[ 2 ], $colInfo[ 1 ], $fieldVal );

    #  Return to the caller
    #
    return $fieldVal;
}


#############################################
#
#  Retrieve the record in a file of specific
#  type at the record number specified
#
#############################################
#
function getRecord( $fp, $type, $recNum )
{
    global   $structSize, $ciberRecNum;

#echo "in the getRecord( $fp, $type, $recNum )...<br>\n";

    $inLine = '';

    #  If we are reading pipe '|' delimited files,
    #  then we have variable length records,
    #  so read lines until we get to the
    #  correct record
    #
    if ( $type == 1 || $type == 2 || $type == 9 )
    {
        #  Read lines from the file
        #  up to the record we want
        #
        for ( $i = 0; $i <= $recNum; $i++ )
        {
            #  Read a line from the input file
            #  return FALSE on failure
            #
            if ( !( $inLine = fgets( $fp ) ) )
                return FALSE;
        }
    }
    #  If we are looking at a Ciber record
    #
    elseif ( $type == 12 )
    {
        #  Read lines from the file
        #  up to the requested record
        #
        for ( $i = 0; $i <= $recNum; $i++ )
        {
            #  Read a line from the input file
            #  return FALSE on failure
            #
            if ( !( $inLine = fgets( $fp ) ) )
                return FALSE;
        }

        #  Set the global "Ciber" record
        #  number to the current record number
        #
        $ciberRecNum = $recNum;

        #  Read lines from the file
        #  until we get a record 22
        #
        while ( intVal( substr( $inLine, 0, 2 ) ) != 22 )
        {
            #  Advance the ciber record number
            #
            $ciberRecNum++;

            #  Read a line from the input file
            #  return FALSE on failure
            #
            if ( !( $inLine = fgets( $fp ) ) )
                return FALSE;
        }
    }
    else
    {
        #  Position the file pointer to the
        #  proper record, return FALSE if failure
        #
        if ( fseek( $fp, $recNum * $structSize[ $type ], SEEK_SET ) == -1 )
            return FALSE;

        #  Read a line from the input file
        #
        $inLine = fread( $fp, $structSize[ $type ] );
    }

    return $inLine;
}


##########################################
#
#  Return the human readable form a field
#  given the field, the field type and 
#  the field size
#
##########################################
#
function convertFieldToVal( $type, $size, $field )
{
    global $debug;

    $retVal = '';

    #  Depending on the field type,
    #  compute the value of the field
    #  in ASCII
    switch( strtoupper( $type ) )
    {
    case "AN":  //  alphanumeric
       $retVal = $field;

       #  If debugging is turned on,
       #  show the hex dump of the field
       #
       if ( $debug )
       {
           $hexStr = '';

           for ( $i = 0; $i < strlen( $field ); $i++ )
           {
               $hexStr .= sprintf( "%02X ", ord( substr( $field, $i, 1 ) ) );
           }

           $retVal .= ' - (' . $hexStr . ')';
       }

       break;

    case "UN":  //  unsigned numeric
       $retVal = $field;
       break;

    case "BI":  //  binary data

       $retVal = 0;
       $hexStr = '[' . strlen( $field ) . '] - ';

       #  Strip off leading and trailing spaces
       #
#       $field = preg_replace( '/^\x20*(.*?)\x20*$/', '\\1', $field );

       $hexStr .= '{' . strlen( $field ) . '} - ';

       #  Loop through the string,
       #  and accumulate the binary
       #  values
       #
       for ( $i = 0; $i < strlen( $field ); $i++ )
       {
           #  Retrieve the ASCII value of
           #  the character in the field
           #
           $chVal   = ord( substr( $field, $i, 1 ) );

           #  Accumulate the binary numbers
           #
           $retVal *= 256;
           $retVal += $chVal;

           $hexStr .= sprintf( "%02X ", $chVal );
       }

       $retVal = strval( sprintf( "%.0f", doubleval( $retVal ) ) );

       #  If we are debugging, display the hex code
       #  after the return value
       #
       if ( $debug ) $retVal .= ' - ' . $hexStr;

       break;

    case "SP":  //  signed packed data

       $retVal       = '0';
       $hexStr = '[' . strlen( $field ) . '] - ';
     
       #  Strip off leading spaces
       #
       $field = preg_replace( '/^\x20*(.*?)$/', '\\1', $field );

       $hexStr .= '{' . strlen( $field ) . '} - ';

       #  Each character in the SP format
       #  is a BSD represenation of the
       #  number.  Loop through each
       #  character, and concatinate the
       #  BSD reprentation of the character
       #  to our return value.  The last
       #  character only uses the high-nibble
       #  as a digit
       #
       for ( $i = 0; $i < $size - 1; $i++ )
       {
           #  Retrieve the ASCII value of
           #  the character in the field
           #
           $chVal   = ord( substr( $field, $i, 1 ) );

           #  Add the 2-digit BSD to the
           #  end of the return value string
           #
           $retVal .= sprintf( "%02x", $chVal );

           #$hexStr .= sprintf( "%02x ", $chVal );
       }

       #  Use only the high nibble of the last
       #  character in the field as the last digit
       #
       $retVal .=  ord( substr( $field, $size - 1, 1 ) ) >> 4;

       $hexStr .= sprintf( "%02x", ord( substr( $field, $size - 1 , 1 ) ) );
     
       if ( $debug ) $retVal .= ' - ' . $hexStr;

       break;
    }

    return $retVal;
}


##############################################
#
#  Return the record structure array
#  associated with a particular record type
#
##############################################
#
function getRecStruct( $type )
{
    #  Define our global record format structures
    #
    global $udrspStruct, $udrsp2Struct, $udrStruct, $udr2Struct, $calCDR2Struct;
    global $calCDRStruct, $datCDRStruct, $smsCDRStruct, $udrchStruct;
    global $cingFixStruct, $cib0222Struct, $udrsdStruct, $ntStruct;
    global $ntgsmStructR02;                                           # 07-10-06
    global $lucfmtrec_2329;                                           # 07-11-06
    global $gprs_opn020;                                              # 07-12-06

    #  Depending on the record type
    #  of the file, assign our
    #  record structure
    #
    switch ( $type )
    {
    case 0:  //  udrsp v1.0
        $recStruct = $udrspStruct;
        break;
    
    case 1:  //  udr v1.0 (pipe)
        $recStruct = $udrStruct;
        break;
    
    case 2:  //  udr v2.0 (pipe)
        $recStruct = $udr2Struct;
        break;
    
    case 3:  //  CALCDR
        $recStruct = $calCDRStruct;
        break;
    
    case 10:  //  CALCDR2
        $recStruct = $calCDR2Struct;
        break;
    
    case 4:  //  DATCDR
        $recStruct = $datCDRStruct;
        break;
    
    case 5:  //  SMSCDR
        $recStruct = $smsCDRStruct;
        break;
    
    case 6:  //  udr
        $recStruct = $udrStruct;
        break;
    
    case 7:  //  udr v2.0
        $recStruct = $udr2Struct;
        break;

    case 8:  //  udrsp v2.0
        $recStruct = $udrsp2Struct;
        break;

    case 9:  //  udrch v1.0 (pipe)
        $recStruct = $udrchStruct;
        break;

    case 11:  //  Cingular Fixed Rec
        $recStruct = $cingFixStruct;
        break;

    case 12:  //  Ciber Type 22
        $recStruct = $cib0222Struct;
        break;

    case 13:  //  udrsd Struct 
        $recStruct = $udrsdStruct;
        break;

    case 14:  //  udrsd Struct 
        $recStruct = $ntStruct;
        break;

    case 15:  //  Nortel GSM Struct Mobile to Land                    # 07-10-06
        $recStruct = $ntgsmStructR02;                                 # 07-10-06
        break;                                                        # 07-10-06
                                                                      # 07-10-06
    case 16:  //  Lucent Struct                                       # 07-11-06
        $recStruct = $lucfmtrec_2329;                                 # 07-11-06
        break;                                                        # 07-11-06
                                                                      # 07-11-06
    case 17:  // gprs                                                 # 07-12-06
        $recStruct = $gprs_opn020;                                    # 07-12-06
        break;                                                        # 07-12-06
                                                                      # 07-12-06
    default:
        #  Default to UDRSP if unkown
        #
        $recStruct = $udrspStruct;
        break;
    }

    #  Return the record structure
    #  to the caller
    #
    return $recStruct;
}

##############################################                        # 07-10-06
#                                                                     # 07-10-06
# Nortel GSM has two record format variants                           # 07-10-06
#                                                                     # 07-10-06
##############################################                        # 07-10-06
#                                                                     # 07-10-06
function getNorGsmStrCode( $inLine )                                  # 07-10-06
{                                                                     # 07-10-06
    if ( '0002' == substr($inLine, 7, 4))                             # 07-10-06
    {                                                                 # 07-10-06
        $strCode = 2;                                                 # 07-10-06
    }                                                                 # 07-10-06
    else if ( '0003' == substr($inLine, 7, 4))                        # 07-10-06
    {                                                                 # 07-10-06
        $strCode = 3;                                                 # 07-10-06
    }                                                                 # 07-10-06
    else                                                              # 07-10-06
    {                                                                 # 07-10-06
        $strCode = 99;                                                # 07-10-06
    }                                                                 # 07-10-06
                                                                      # 07-10-06
    return $strCode;                                                  # 07-10-06
}                                                                     # 07-10-06
                                                                      # 07-10-06
##############################################                        # 07-10-06
#                                                                     # 07-10-06
# Nortel GSM has two record format variants                           # 07-10-06
#                                                                     # 07-10-06
##############################################                        # 07-10-06
#                                                                     # 07-10-06
function getNorGsmStruct( $inLine )                                   # 07-10-06
{                                                                     # 07-10-06
    global $ntgsmStructR02, $ntgsmStructR03, $ntgsmStructR99;         # 07-10-06
                                                                      # 07-10-06
    $strCode = getNorGsmStrCode ($inLine);                            # 07-10-06
    if ( 2 == $strCode)                                               # 07-10-06
    {                                                                 # 07-10-06
        $recStruct = $ntgsmStructR02;                                 # 07-10-06
    }                                                                 # 07-10-06
    else if ( 3 == $strCode)                                          # 07-10-06
    {                                                                 # 07-10-06
        $recStruct = $ntgsmStructR03;                                 # 07-10-06
    }                                                                 # 07-10-06
    else                                                              # 07-10-06
    {                                                                 # 07-10-06
        $recStruct = $ntgsmStructR99;                                 # 07-10-06
    }                                                                 # 07-10-06
                                                                      # 07-10-06
    return $recStruct;                                                # 07-10-06
}                                                                     # 07-10-06
                                                                      # 07-10-06
##############################################                        # 07-11-06
#                                                                     # 07-11-06
# Lucent has two record format variants                               # 07-11-06
#                                                                     # 07-11-06
##############################################                        # 07-11-06
#                                                                     # 07-11-06
function getLucentStrCode( $inLine )                                  # 07-11-06
{                                                                     # 07-11-06
    if ( '2329' == substr($inLine, 11, 4))                            # 07-11-06
    {                                                                 # 07-11-06
        $strCode = 2329;                                              # 07-11-06
    }                                                                 # 07-11-06
    else if ( '2331' == substr($inLine, 11, 4))                       # 07-11-06
    {                                                                 # 07-11-06
        $strCode = 2331;                                              # 07-11-06
    }                                                                 # 07-11-06
    else                                                              # 07-11-06
    {                                                                 # 07-11-06
        $strCode = 9999;                                              # 07-11-06
    }                                                                 # 07-11-06
                                                                      # 07-11-06
    return $strCode;                                                  # 07-11-06
}                                                                     # 07-11-06
                                                                      # 07-11-06
##############################################                        # 07-11-06
#                                                                     # 07-11-06
# Lucent has two record format variants                               # 07-11-06
#                                                                     # 07-11-06
##############################################                        # 07-11-06
#                                                                     # 07-11-06
function getLucentStruct( $inLine )                                   # 07-11-06
{                                                                     # 07-11-06
    global $lucfmtrec_2329, $lucfmtrec_2331, $lucfmtrec_9999;         # 07-11-06
                                                                      # 07-11-06
    $strCode = getLucentStrCode ($inLine);                            # 07-11-06
    if ( 2329 == $strCode)                                            # 07-11-06
    {                                                                 # 07-11-06
        $recStruct = $lucfmtrec_2329;                                 # 07-11-06
    }                                                                 # 07-11-06
    else if ( 2331 == $strCode)                                       # 07-11-06
    {                                                                 # 07-11-06
        $recStruct = $lucfmtrec_2331;                                 # 07-11-06
    }                                                                 # 07-11-06
    else                                                              # 07-11-06
    {                                                                 # 07-11-06
        $recStruct = $lucfmtrec_9999;                                 # 07-11-06
    }                                                                 # 07-11-06
                                                                      # 07-11-06
    return $recStruct;                                                # 07-11-06
}                                                                     # 07-11-06
                                                                      # 07-11-06
?>
