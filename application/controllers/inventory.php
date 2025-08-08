<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('data_model', 'config_model', 'inventory_model','report_model'));
        $this->load->helper('url');
        // Remove session_start() - CodeIgniter handles sessions automatically
    }
    
    public function index() {
        try {
            $data = array(); // Initialize $data first
            $data = $this->load_top($data);
            $config = $this->config_model->getConfig();
            redirect($config['base_url'] . 'inventory/inv_ecct');
        } catch (Exception $e) {
            log_message('error', 'Inventory index error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }
    
    public function all_item() {
        try {
            $data['onload'] = "showDataItem();";
            $data = $this->load_top($data);
            $data['title_page'] = "All Items";
            $this->load->view('inventory/banner', $data);
            $this->load->view('inventory/all_item', $data);
            $this->load->view('inventory/javascript', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'All item error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }

    public function inv_report() {
        try {
            $data['onload'] = "showData();";
            $data = $this->load_top($data);
            $data['title_page'] = "Inventory Report";
            $data['dvc_code'] = $this->report_model->getDevicesForReport('ecbs', 'app'); // Default data
            $this->load->view('inventory/banner', $data);
            $this->load->view('report/inv_report', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'Inventory report page error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }
    
    public function massive_input() {
        try {
            $data['onload'] = "";
            $data = $this->load_top($data);
            $data['title_page'] = "Massive Inventory Input";
            $this->load->view('inventory/banner', $data);
            $this->load->view('inventory/massive_input', $data);
            $this->load->view('inventory/javascript', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'Massive input error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }
    
    public function inv_ecct() {
        try {
            $data['onload'] = "showDataEcct();";
            $data = $this->load_top($data);
            $data['title_page'] = "Inventory ECCT";
            $data['dvc_code'] = $this->inventory_model->getDeviceTypes('ecct');
            $this->load->view('inventory/banner', $data);
            $this->load->view('inventory/inv_ecct', $data);
            $this->load->view('inventory/javascript', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'ECCT inventory error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }
    
    public function inv_ecbs() {
        try {
            $data['onload'] = "showDataEcbs();";
            $data = $this->load_top($data);
            $data['title_page'] = "Inventory ECBS";
            $data['dvc_code'] = $this->inventory_model->getDeviceTypes('ecbs');
            $this->load->view('inventory/banner', $data);
            $this->load->view('inventory/inv_ecbs', $data);
            $this->load->view('inventory/javascript', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'ECBS inventory error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }
    
    public function inv_week() {
        try {
            $data['onload'] = "showInvWeekData();";
            $data = $this->load_top($data);
            $data['title_page'] = "Inventory Weekly Period Management";
            $this->load->view('inventory/banner', $data);
            $this->load->view('report/inv_week', $data);
            $this->load->view('inventory/javascript', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'Inv week error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }

    public function inv_report_needs() {
        try {
            $data['onload'] = "";
            $data = $this->load_top($data);
            $data['title_page'] = "Inventory Report Needs";
            $this->load->view('inventory/banner', $data);
            $this->load->view('report/inv_report_needs', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'Report needs error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
        }
    }
    
    public function input_process() {
        try {
            $data = $this->_get_json_input();
            
            if (!$data) {
                $this->_json_response(false, 'Invalid input data');
                return;
            }
            
            // Process the input data
            $result = $this->data_model->processInventoryInput($data);
            
            if ($result) {
                // Auto-update stock values for active weeks when inv_act changes
                $this->report_model->updateInventoryReportStockAuto();
                
                $this->_json_response(true, 'Data processed successfully');
            } else {
                $this->_json_response(false, 'Failed to process data');
            }
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Input process error', true);
        }
    }

    public function data($type = "", $input = "") {
        try {
            if ($this->input->is_ajax_request()) {
                $data = $this->_prepare_view_data();
                
                switch ($type) {
                    case "item":
                        $result = $this->data_model->getAllItem(20);
                        $data['result'] = $result;
                        $this->load->view('inventory/data/data_inv_app_show', $data);
                        break;
                        
                    case "ecct":
                        $result = $this->data_model->getAllItemByTech('ecct', 20);
                        $data['result'] = $result;
                        $this->load->view('inventory/data/data_inv_ecct_show', $data);
                        break;
                        
                    case "ecbs":
                        $result = $this->data_model->getAllItemByTech('ecbs', 20);
                        $data['result'] = $result;
                        $this->load->view('inventory/data/data_inv_ecbs_show', $data);
                        break;
                        
                    case "week":
                        $result = $this->data_model->getInvWeekData();
                        $data['result'] = $result;
                        $this->load->view('inventory/data/data_inv_week_show', $data);
                        break;
                        
                    default:
                        echo "Invalid data type";
                        break;
                }
                
                // Auto-update stock values for active weeks when data is loaded
                $this->report_model->updateInventoryReportStockAuto();
                
            } else {
                show_404();
            }
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Data load error', $this->input->is_ajax_request());
        }
    }


    public function check_inv_week_periods() {
        try {
            $year = $this->input->get('year');
            $month = $this->input->get('month');
            
            if (!$year || !$month) {
                return $this->_output_json($this->_json_response(false, 'Year and month are required'));
            }

            $year = intval($year);
            $month = intval($month);

            // Validate year and month ranges
            if ($year < 2020 || $year > 2030) {
                return $this->_output_json($this->_json_response(false, 'Invalid year range'));
            }
            
            if ($month < 1 || $month > 12) {
                return $this->_output_json($this->_json_response(false, 'Invalid month range'));
            }

            $exists = $this->report_model->periods_exist($year, $month);
            return $this->_output_json($this->_json_response(true, '', array('exists' => $exists)));
        } catch (Exception $e) {
            log_message('error', 'Check periods error: ' . $e->getMessage());
            return $this->_output_json($this->_json_response(false, 'Error: ' . $e->getMessage()));
        }
    }

    public function generate_inv_week_periods() {
        try {
            $input_data = $this->_get_json_input();
            if (!$input_data) {
                return $this->_output_json($this->_json_response(false, 'Invalid JSON input'));
            }

            $year = isset($input_data['year']) ? intval($input_data['year']) : null;
            $month = isset($input_data['month']) ? intval($input_data['month']) : null;
            $regenerate = isset($input_data['regenerate']) ? boolval($input_data['regenerate']) : false;

            if (!$year || !$month) {
                return $this->_output_json($this->_json_response(false, 'Year and month are required'));
            }

            // Validate year and month ranges
            if ($year < 2020 || $year > 2030) {
                return $this->_output_json($this->_json_response(false, 'Invalid year range'));
            }
            
            if ($month < 1 || $month > 12) {
                return $this->_output_json($this->_json_response(false, 'Invalid month range'));
            }

            $result = $this->report_model->generate_weekly_periods($year, $month, $regenerate);
            
            $action = $regenerate ? 'regenerated' : 'generated';
            $message = "Periods {$action} successfully with 27th-26th logic and 08:00-17:00 time schedule. Total periods: " . count($result);
            return $this->_output_json($this->_json_response(true, $message, $result));
        } catch (Exception $e) {
            log_message('error', 'Generate periods error: ' . $e->getMessage());
            return $this->_output_json($this->_json_response(false, 'Error: ' . $e->getMessage()));
        }
    }

    public function update_inv_week_period() {
        try {
            $input_data = $this->_get_json_input();
            if (!$input_data) {
                return $this->_output_json($this->_json_response(false, 'Invalid JSON input'));
            }

            $id_week = isset($input_data['id_week']) ? intval($input_data['id_week']) : null;
            $date_start = isset($input_data['date_start']) ? $input_data['date_start'] : null;
            $date_finish = isset($input_data['date_finish']) ? $input_data['date_finish'] : null;

            if (!$id_week || !$date_start || !$date_finish) {
                return $this->_output_json($this->_json_response(false, 'All fields are required'));
            }

            // Validate date format
            $start_dt = DateTime::createFromFormat('Y-m-d\TH:i', $date_start);
            $finish_dt = DateTime::createFromFormat('Y-m-d\TH:i', $date_finish);
            
            if (!$start_dt || !$finish_dt) {
                return $this->_output_json($this->_json_response(false, 'Invalid date format'));
            }

            if ($start_dt >= $finish_dt) {
                return $this->_output_json($this->_json_response(false, 'Start date must be before finish date'));
            }

            $result = $this->report_model->update_inv_week($id_week, $date_start, $date_finish);
            
            if ($result) {
                $message = "Period updated successfully. Times automatically set to 08:00-17:00";
                return $this->_output_json($this->_json_response(true, $message));
            } else {
                return $this->_output_json($this->_json_response(false, 'Failed to update period'));
            }
        } catch (Exception $e) {
            log_message('error', 'Update period error: ' . $e->getMessage());
            return $this->_output_json($this->_json_response(false, 'Error: ' . $e->getMessage()));
        }
    }

    public function export_inv_week() {
        try {
            $year = $this->input->get('year');
            $month = $this->input->get('month');
            
            if (!$year || !$month) {
                redirect('inventory/inv_week');
                return;
            }

            $data = $this->report_model->get_inv_week_data($year, $month);
            $this->exportInvWeekData($data, 'inv_week_' . $year . '_' . $month);
        } catch (Exception $e) {
            log_message('error', 'Export inv week error: ' . $e->getMessage());
            redirect('inventory/inv_week');
        }
    }

    private function exportInvWeekData($data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, array('ID Week', 'Year', 'Month', 'Week', 'Date Start', 'Date Finish', 'Duration (Days)'));
        
        // Data
        foreach ($data as $row) {
            $start_date = new DateTime($row['date_start']);
            $finish_date = new DateTime($row['date_finish']);
            $duration = $start_date->diff($finish_date)->days + 1;
            
            fputcsv($output, array(
                $row['id_week'],
                $row['period_y'],
                $row['period_m'],
                $row['period_w'],
                $start_date->format('d/m/Y H:i'),
                $finish_date->format('d/m/Y H:i'),
                $duration
            ));
        }
        
        fclose($output);
        exit;
    }

    private $report_configs = array(
        'report_ecbs_app' => array('tech' => 'ecbs', 'type' => 'app', 'view' => 'report/needs/report_app_show', 'export' => 'ECBS_APP_Report'),
        'report_ecct_app' => array('tech' => 'ecct', 'type' => 'app', 'view' => 'report/needs/report_app_show', 'export' => 'ECCT_APP_Report'),
        'report_ecbs_osc' => array('tech' => 'ecbs', 'type' => 'osc', 'view' => 'report/needs/report_osc_show', 'export' => 'ECBS_OSC_Report'),
        'report_ecct_osc' => array('tech' => 'ecct', 'type' => 'osc', 'view' => 'report/needs/report_osc_show', 'export' => 'ECCT_OSC_Report'),
    );
    
    private $inv_report_configs = array(
        'report_ecbs_app' => array('tech' => 'ecbs', 'type' => 'app', 'view' => 'report/report/report_app_show', 'export' => 'ECBS_APP_Inventory_Report'),
        'report_ecct_app' => array('tech' => 'ecct', 'type' => 'app', 'view' => 'report/report/report_app_show', 'export' => 'ECCT_APP_Inventory_Report'),
        'report_ecbs_osc' => array('tech' => 'ecbs', 'type' => 'osc', 'view' => 'report/report/report_osc_show', 'export' => 'ECBS_OSC_Inventory_Report'),
        'report_ecct_osc' => array('tech' => 'ecct', 'type' => 'osc', 'view' => 'report/report/report_osc_show', 'export' => 'ECCT_OSC_Inventory_Report'),
    );

    public function report($type = "", $input = "") {
        try {
            $data = $this->_prepare_view_data();
            $base_type = str_replace(array('_show', '_export'), '', $type);
            $action = (strpos($type, '_export') !== false) ? 'export' : 'show';
            
            if (!isset($this->report_configs[$base_type])) {
                $this->_show_error("Report not found");
                return;
            }
            
            $config = $this->report_configs[$base_type];
            $data['data'] = $this->report_model->getReportData($config['tech'], $config['type']);
            
            if ($action === 'export') {
                $this->_export_csv($data['data'], $config['export'], array('ID', 'Device Code', 'Device Name', 'Status'), 
                    function($row) {
                        return array($row['id_dvc'], $row['dvc_code'], $row['dvc_name'], $row['status']);
                    });
                return;
            }
            
            $data['existing_needs'] = $this->report_model->getExistingNeedsData($config['tech'], $config['type']);
            $data['tech'] = $config['tech'];
            $data['device_type'] = $config['type'];
            
            $this->_render_view($config['view'], $data);
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Report error');
        }
    }
    
    /**
     * Inventory report data method (renamed from inv_report)
     */
    public function inv_report_data($type = "", $input = "") {
        try {
            $data = $this->_prepare_view_data();
            $base_type = str_replace(array('_show', '_export'), '', $type);
            $action = (strpos($type, '_export') !== false) ? 'export' : 'show';
            
            if (!isset($this->inv_report_configs[$base_type])) {
                $this->_show_error("Inventory Report not found");
                return;
            }
            
            $config = $this->inv_report_configs[$base_type];
            $data['data'] = $this->report_model->getInventoryReportData($config['tech'], $config['type']);
            
            if ($action === 'export') {
                $this->_export_csv($data['data'], $config['export'], 
                    array('ID PMS', 'Week', 'Device Code', 'Device Name', 'Size', 'Color', 'QC', 'Stock', 'On PMS', 'Needs', 'Order', 'Over'),
                    function($row) {
                        $week_display = 'W' . $row['period_w'] . '/' . $row['period_m'] . '/' . $row['period_y'];
                        return array(
                            $row['id_pms'], $week_display, $row['dvc_code'], $row['dvc_name'],
                            strtoupper($row['dvc_size']), $row['dvc_col'] === '' ? '-' : $row['dvc_col'],
                            $row['dvc_qc'], intval($row['stock']), intval($row['on_pms']),
                            intval($row['needs']), intval($row['order']), intval($row['over'])
                        );
                    });
                return;
            }
            
            $data['tech'] = $config['tech'];
            $data['device_type'] = $config['type'];
            
            $this->_render_view($config['view'], $data);
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Inventory Report error');
        }
    }
    
    /**
     * Save all needs data
     */
    public function save_all_needs_data() {
        try {
            $data = $this->input->post('data');
            
            if (!is_array($data)) {
                $this->_json_response(false, 'Invalid data format');
                return;
            }
            
            $success_count = 0;
            $actions = array();
            
            foreach ($data as $item) {
                $result = $this->_process_needs_item($item);
                if ($result['success']) {
                    $success_count++;
                }
                $actions[] = $result['action'];
            }
            
            // Auto-update inventory report after needs save
            // This will update needs values for current active weeks only
            $this->report_model->updateInventoryReportNeedsAuto();
            
            // Auto-update stock values for current active weeks only
            $this->report_model->updateInventoryReportStockAuto();
            
            $this->_json_response(true, 'Data processed', array(
                'success' => $success_count,
                'total' => count($data),
                'actions' => array_count_values($actions)
            ));
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Save all needs data error', true);
        }
    }
    
    /**
     * Save On PMS data - Fixed method
     */
    public function save_on_pms() {
        try {
            $data = array(
                'id_week' => $this->input->post('id_week'),
                'id_dvc' => $this->input->post('id_dvc'),
                'dvc_size' => $this->input->post('dvc_size'),
                'dvc_col' => $this->input->post('dvc_col'),
                'dvc_qc' => $this->input->post('dvc_qc'),
                'on_pms' => intval($this->input->post('on_pms'))
            );
            
            // Validate required fields
            if (empty($data['id_week']) || empty($data['id_dvc']) || empty($data['dvc_size']) || 
                empty($data['dvc_col']) || empty($data['dvc_qc']) || $data['on_pms'] < 0) {
                $this->_json_response(false, 'Invalid input data');
                return;
            }
            
            $result = $this->report_model->saveOnPmsData($data);
            
            $this->_json_response($result, $result ? 'On PMS data saved successfully' : 'Failed to save data');
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Save On PMS error', true);
        }
    }
    
    /**
     * Get device colors
     */
    public function get_device_colors() {
        try {
            $id_dvc = $this->input->get('id_dvc');
            $colors = $this->report_model->getDeviceColors($id_dvc);
            
            $this->_json_response(true, 'Colors loaded', array('colors' => $colors));
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Error loading device colors', true);
        }
    }
    
    /**
     * Get week periods
     */
    public function get_week_periods() {
        try {
            $weeks = $this->report_model->getWeekPeriods();
            $this->_json_response(true, 'Week periods loaded', array('weeks' => $weeks));
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Error loading week periods', true);
        }
    }
    
    /**
     * Get devices for report
     */
    public function get_devices_for_report() {
        try {
            $tech = $this->input->get('tech');
            $type = $this->input->get('type');
            
            $devices = $this->report_model->getDevicesForReport($tech, $type);
            $this->_json_response(true, 'Devices loaded', array('devices' => $devices));
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Error loading devices', true);
        }
    }
    
    /**
     * Generate inventory report data
     */
    public function generate_inventory_report() {
        try {
            $result = $this->report_model->generateInventoryReportData();
            $message = $result ? 'Inventory report data generated successfully' : 'Failed to generate inventory report data';
            
            if ($this->input->is_ajax_request()) {
                $this->_json_response($result, $message);
            } else {
                echo $message;
            }
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Generate inventory report error', $this->input->is_ajax_request());
        }
    }
        
    /**
     * Auto-update stock when inv_act changes
     */
    public function update_inventory_stock() {
        try {
            $result = $this->report_model->updateInventoryReportStockAuto();
            $message = $result ? 'Stock values auto-updated successfully' : 'Failed to auto-update stock values';
            
            if ($this->input->is_ajax_request()) {
                $this->_json_response($result, $message);
            } else {
                echo $message;
            }
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Auto-update inventory stock error', $this->input->is_ajax_request());
        }
    }
    
    /**
     * Auto-update needs when inv_needs changes
     */
    public function update_inventory_needs() {
        try {
            $result = $this->report_model->updateInventoryReportNeedsAuto();
            $message = $result ? 'Needs values auto-updated successfully' : 'Failed to auto-update needs values';
            
            if ($this->input->is_ajax_request()) {
                $this->_json_response($result, $message);
            } else {
                echo $message;
            }
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Auto-update inventory needs error', $this->input->is_ajax_request());
        }
    }
    
    // =============== PRIVATE HELPER METHODS ===============
    
    private function _prepare_view_data() {
        return !$this->input->is_ajax_request() ? $this->load_top("", "no_view") : array();
    }
    
    private function _render_view($view, $data) {
        $this->load->view($view, $data);
        if (!$this->input->is_ajax_request()) {
            $this->load_bot($data, "no_view");
        }
    }
    
    private function _show_error($message) {
        echo "<div style='padding: 20px; text-align: center;'><h3>{$message}</h3></div>";
    }
    
    private function _export_csv($data, $filename, $headers, $row_callback) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
        
        fputcsv($output, $headers);
        foreach ($data as $row) {
            fputcsv($output, $row_callback($row));
        }
        
        fclose($output);
        exit;
    }
    
    private function _process_needs_item($item) {
        // Set default values
        $item = array_merge(array(
            'id_dvc' => '', 'dvc_size' => '', 'dvc_col' => '', 'dvc_qc' => '',
            'needs_qty' => 0, 'original_qty' => 0
        ), $item);
        
        // Validate and convert
        if (empty($item['id_dvc']) || $item['id_dvc'] == '0') {
            return array('success' => false, 'action' => 'invalid');
        }
        
        // Validate QC value - should be 'LN' or 'DN'
        if (!in_array($item['dvc_qc'], array('LN', 'DN'))) {
            return array('success' => false, 'action' => 'invalid_qc');
        }
        
        $item['needs_qty'] = intval($item['needs_qty']);
        $item['original_qty'] = intval($item['original_qty']);
        
        // Skip if no change
        if ($item['needs_qty'] === $item['original_qty']) {
            return array('success' => true, 'action' => 'unchanged');
        }
        
        // Get device info to determine proper color
        $this->db->select('dvc_code, dvc_tech, dvc_type');
        $this->db->from('inv_dvc');
        $this->db->where('id_dvc', $item['id_dvc']);
        $device_query = $this->db->get();
        
            if ($device_query->num_rows() > 0) {
            $device = $device_query->row_array();
            $dvc_code = strtoupper($device['dvc_code']);
            $dvc_tech = strtolower($device['dvc_tech']);
            $dvc_type = strtoupper($device['dvc_type']);
            
            // Determine color based on device properties
            if (stripos($dvc_code, 'VOH') === 0) {
                // For VOH devices, use the exact color text from the form (e.g., "Dark Gray")
                $item['dvc_col'] = $item['dvc_col'];
            } elseif ($dvc_tech == 'ecct' && $dvc_type == 'APP') {
                // ECCT APP devices should be "Dark Grey" (store display text)
                $item['dvc_col'] = 'Dark Grey';
            } elseif ($dvc_tech == 'ecbs' && $dvc_type == 'APP') {
                // ECBS APP devices should be "Black"
                $item['dvc_col'] = 'Black';
            } elseif ($dvc_type == 'OSC') {
                // OSC devices should have empty string
                $item['dvc_col'] = ' ';
            } else {
                // For other devices, keep what the form sends (if any)
                $item['dvc_col'] = $item['dvc_col'];
            }
        } else {
            // Fallback: keep as provided
            $item['dvc_col'] = $item['dvc_col'];
        }
        
        $db_data = array(
            'id_dvc' => $item['id_dvc'], 
            'dvc_size' => $item['dvc_size'],
            'dvc_col' => $item['dvc_col'], 
            'dvc_qc' => $item['dvc_qc'],
            'needs_qty' => $item['needs_qty']
        );
        
        // Delete if quantity is 0 or less
        if ($item['needs_qty'] <= 0) {
            $result = $this->report_model->deleteNeedsData($item['id_dvc'], $item['dvc_size'], $item['dvc_col'], $item['dvc_qc']);
            return array('success' => $result, 'action' => 'deleted');
        }
        
        // Check if exists and update or insert
        $existing = $this->report_model->getNeedsData($item['id_dvc'], $item['dvc_size'], $item['dvc_col'], $item['dvc_qc']);
        
        if ($existing) {
            $result = $this->report_model->updateNeedsData($existing['id_needs'], array('needs_qty' => $item['needs_qty']));
            return array('success' => $result, 'action' => 'updated');
        } else {
            $result = $this->report_model->saveNeedsData($db_data);
            return array('success' => $result, 'action' => 'inserted');
        }
    }

    protected function _get_json_input() {
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    private function _output_json($data) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    private function _json_response($success, $message, $data = null) {
        $response = array('success' => $success, 'message' => $message);
        if ($data !== null) {
            $response = array_merge($response, $data);
        }
        echo json_encode($response);
    }
    
    private function _handle_error($e, $log_message, $is_json = false) {
        log_message('error', $log_message . ': ' . $e->getMessage());
        
        if ($is_json) {
            $this->_json_response(false, 'An error occurred');
        } else {
            show_error('An error occurred while processing your request.');
        }
    }
    
    // =============== LOAD METHODS ===============
    
    protected function load_top($data = "", $view = "", $access = "") {
        $this->load->model("load_model");
        return $this->load_model->load_top_v3($data, $view, $access);
    }
    
    protected function load_bot($data = "", $view = "") {
        $this->load->model("load_model");
        $this->load_model->load_bot_v3($data, $view);
    }
}
?>
