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
            $input_data = $this->_get_json_input();
            if (!$input_data) {
                return $this->_output_json($this->_json_response(false, 'Invalid JSON input'));
            }
            
            $type = $this->input->get_post('type') ?: (isset($input_data['type']) ? $input_data['type'] : null);
            
            if (!in_array($type, ['in', 'out', 'move'])) {
                return $this->_output_json($this->_json_response(false, 'Invalid process type'));
            }
            
            switch ($type) {
                case 'in':
                    $result = $this->inventory_model->processInventoryIn($input_data);
                    break;
                case 'out':
                    $result = $this->inventory_model->processInventoryOut($input_data);
                    break;
                case 'move':
                    $result = $this->inventory_model->processInventoryMove($input_data);
                    break;
                default:
                    $result = $this->_json_response(false, 'Invalid process type');
            }
            
            return $this->_output_json($result);
        } catch (Exception $e) {
            log_message('error', 'Input process error: ' . $e->getMessage());
            return $this->_output_json($this->_json_response(false, 'Error: ' . $e->getMessage()));
        }
    }

    public function data($type = "", $input = "") {
        try {
            $data = $this->load_top("", "no_view");
            
            // Validate type parameter
            if (empty($type)) {
                show_404();
                return;
            }
            
            // Handle data_inv_week_show data display
            if ($type == 'data_inv_week_show') {
                $year = $this->uri->segment(4);
                $month = $this->input->get('month');
                
                if ($year && $month) {
                    $data['data'] = $this->report_model->get_inv_week_data($year, $month);
                } else {
                    $data['data'] = array();
                }
                
                $this->load->view('inventory/data/data_inv_week_show', $data);
                $this->load_bot($data, "no_view");
                return;
            }
            
            // Mapping type ke handler dan parameter
            $type_map = array(
                // APP
                'data_inv_app_show' => array('handler' => 'getDeviceStockApp', 'view' => 'inventory/data/data_inv_app_show'),
                'data_inv_ecct_app_show' => array('handler' => 'getDeviceStockApp', 'view' => 'inventory/data/data_inv_app_show', 'tech' => 'ecct'),
                'data_inv_ecbs_app_show' => array('handler' => 'getDeviceStockApp', 'view' => 'inventory/data/data_inv_app_show', 'tech' => 'ecbs'),
                'data_inv_app_export' => array('handler' => 'getDeviceStockApp', 'view' => 'inventory/data/data_inv_app_export'),
                'data_inv_ecct_app_export' => array('handler' => 'getDeviceStockApp', 'view' => 'inventory/data/data_inv_app_export', 'tech' => 'ecct'),
                'data_inv_ecbs_app_export' => array('handler' => 'getDeviceStockApp', 'view' => 'inventory/data/data_inv_app_export', 'tech' => 'ecbs'),
                
                // OSC
                'data_inv_osc_show' => array('handler' => 'getDeviceStockOsc', 'view' => 'inventory/data/data_inv_osc_show'),
                'data_inv_ecct_osc_show' => array('handler' => 'getDeviceStockOsc', 'view' => 'inventory/data/data_inv_osc_show', 'tech' => 'ecct'),
                'data_inv_ecbs_osc_show' => array('handler' => 'getDeviceStockOsc', 'view' => 'inventory/data/data_inv_osc_show', 'tech' => 'ecbs'),
                'data_inv_osc_export' => array('handler' => 'getDeviceStockOsc', 'view' => 'inventory/data/data_inv_osc_export'),
                'data_inv_ecct_osc_export' => array('handler' => 'getDeviceStockOsc', 'view' => 'inventory/data/data_inv_osc_export', 'tech' => 'ecct'),
                'data_inv_ecbs_osc_export' => array('handler' => 'getDeviceStockOsc', 'view' => 'inventory/data/data_inv_osc_export', 'tech' => 'ecbs'),
            );

            if (isset($type_map[$type])) {
                $tech = isset($type_map[$type]['tech']) ? $type_map[$type]['tech'] : 
                       ($this->input->get('tech') ?: $this->input->post('tech') ?: 'ecct');
                $handler = $type_map[$type]['handler'];
                $view = $type_map[$type]['view'];
                $data['data'] = $this->inventory_model->$handler($tech, 999999);
                $this->load->view($view, $data);
                $this->load_bot($data, "no_view");
                return;
            }
            
            switch ($type) {
                case 'data_item_show':
                    $data['data'] = $this->inventory_model->getAllItemByTech('ecct', 10);
                    $this->load->view('inventory/data/data_item_show', $data);
                    break;
                case 'data_item_show_ecbs':
                    $data['data'] = $this->inventory_model->getAllItemByTech('ecbs', 10);
                    $this->load->view('inventory/data/data_item_show', $data);
                    break;
                case 'data_item_export':
                    $context = $this->input->get('context');
                    if ($context === 'inv_ecct') {
                        $data['data'] = $this->inventory_model->getAllItemByTech('ecct', 999999);
                    } elseif ($context === 'inv_ecbs') {
                        $data['data'] = $this->inventory_model->getAllItemByTech('ecbs', 999999);
                    } else {
                        $data['data'] = $this->inventory_model->getAllItemByTech('ecct', 999999);
                    }
                    $this->load->view('inventory/data/data_item_export', $data);
                    break;
                case 'osc_sync_differences':
                    $tech = $this->input->get('tech') ?: 'ecct';
                    $data['differences'] = $this->inventory_model->getOscSyncDifferences($tech);
                    $this->load->view('inventory/data/osc_sync_differences', $data);
                    break;
                default:
                    show_404();
                    return;
            }
            $this->load_bot($data, "no_view");
        } catch (Exception $e) {
            log_message('error', 'Data method error: ' . $e->getMessage());
            show_error('An error occurred while processing the request.');
        }
    }

    // Weekly period management methods
    public function inv_week() {
        try {
            $data['onload'] = "showInvWeekData();";
            $data = $this->load_top($data);
            $data['title_page'] = "Inventory Weekly Period Management";
            $this->load->view('inventory/banner', $data);
            $this->load->view('inventory/inv_week', $data);
            $this->load->view('inventory/javascript', $data);
            $this->load_bot($data);
        } catch (Exception $e) {
            log_message('error', 'Inv week error: ' . $e->getMessage());
            show_error('An error occurred while loading the page.');
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

            $result = $this->report_model->generate_weekly_periods($year, $month);
            
            $message = "Periods generated successfully with 27th-26th logic and 08:00-17:00 time schedule. Total periods: " . count($result);
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
     * Update inventory stock
     */
    public function update_inventory_stock() {
        try {
            $result = $this->report_model->updateInventoryReportStock();
            $message = $result ? 'Inventory stock updated successfully' : 'Failed to update inventory stock';
            
            if ($this->input->is_ajax_request()) {
                $this->_json_response($result, $message);
            } else {
                echo $message;
            }
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Update inventory stock error', $this->input->is_ajax_request());
        }
    }
    
    /**
     * Update inventory needs
     */
    public function update_inventory_needs() {
        try {
            $result = $this->report_model->updateInventoryReportNeeds();
            $message = $result ? 'Inventory needs updated successfully' : 'Failed to update inventory needs';
            
            if ($this->input->is_ajax_request()) {
                $this->_json_response($result, $message);
            } else {
                echo $message;
            }
            
        } catch (Exception $e) {
            $this->_handle_error($e, 'Update inventory needs error', $this->input->is_ajax_request());
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
        
        $item['needs_qty'] = intval($item['needs_qty']);
        $item['original_qty'] = intval($item['original_qty']);
        
        // Skip if no change
        if ($item['needs_qty'] === $item['original_qty']) {
            return array('success' => true, 'action' => 'unchanged');
        }
        
        $db_data = array(
            'id_dvc' => $item['id_dvc'], 'dvc_size' => $item['dvc_size'],
            'dvc_col' => $item['dvc_col'], 'dvc_qc' => $item['dvc_qc'],
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
