<?php
class Data_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function getAllItem($show = 20, $adddata = "", $sort = "") {
        if ($show < 100 && isset($_GET['data_view_item']) && $_GET['data_view_item'] != "") {
            $show = $_GET['data_view_item'];
        }
        $sort = "ORDER BY id_act DESC ";
        $this->load->model("sql_model");
        $filter = $this->sql_model->getFilterItemList($show);
        if ($show >= 999999) {
            $filter['first'] = 0;
        }
        if (isset($filter['sort']) && $filter['sort'] != "") {
            $sort = $filter['sort'];
        }
        $sql = "SELECT inv_act.*, inv_dvc.dvc_name, inv_dvc.dvc_code
                FROM inv_act
                LEFT JOIN inv_dvc ON inv_act.id_dvc = inv_dvc.id_dvc
                WHERE 1=1 ";
                if (isset($filter['all'])) {
            $sql .= " " . $filter['all'] . " ";
        }
                $sql .= " " . $sort . " ";
        $count_sql = str_replace("SELECT inv_act.*, inv_dvc.dvc_name, inv_dvc.dvc_code", "SELECT COUNT(*) as total", $sql);
        $count_query = $this->db->query($count_sql);
        $total_records = $count_query ? $count_query->row()->total : 0;
        $limit = "LIMIT " . $filter['first'] . ", " . $show . " ";
        $main_query = $this->db->query($sql . $limit);

        $data = array();
        $data['query'] = $main_query;
        $data['page']['sum'] = $total_records;
        $data['page']['show'] = $show;
        $data['page']['first'] = $filter['first'];
        return $data;
    }

    public function getAllItemByTech($tech_type, $show = 20, $adddata = "", $sort = "") {
        if (!in_array($tech_type, ['ecct', 'ecbs'])) {
            throw new InvalidArgumentException("Parameter tech_type harus 'ecct' atau 'ecbs'");
        }
        if ($show < 100 && isset($_GET['data_view_item']) && $_GET['data_view_item'] != "") {
            $show = $_GET['data_view_item'];
        }
        $sort = "ORDER BY id_act DESC ";
        $this->load->model("sql_model");
        $filter = $this->sql_model->getFilterItemList($show);
        if ($show >= 999999) {
            $filter['first'] = 0;
        }
        if (isset($filter['sort']) && $filter['sort'] != "") {
            $sort = $filter['sort'];
        }        
        $select_fields = "inv_act.*, inv_dvc.dvc_name, inv_dvc.dvc_code, inv_dvc.dvc_tech, inv_act.dvc_col as warna";
        
        $sql = "SELECT " . $select_fields . " " .
               "FROM inv_act " .
               "INNER JOIN inv_dvc ON inv_act.id_dvc = inv_dvc.id_dvc AND inv_dvc.dvc_tech = '" . $tech_type . "' ";
        if (isset($filter['all'])) {
            $sql .= " " . $filter['all'] . " ";
        }
        $sql .= " " . $sort . " ";
        $count_sql = str_replace("SELECT inv_act.*, inv_dvc.dvc_name, inv_dvc.dvc_code, inv_dvc.dvc_tech, inv_act.dvc_col as warna", "SELECT COUNT(*) as total", $sql);
        $count_query = $this->db->query($count_sql);
        $total_records = $count_query ? $count_query->row()->total : 0;
        $limit = "LIMIT " . $filter['first'] . ", " . $show . " ";
        $main_query = $this->db->query($sql . $limit);

        $data = array();
        $data['query'] = $main_query;
        $data['page']['sum'] = $total_records;
        $data['page']['show'] = $show;
        $data['page']['first'] = $filter['first'];
        if ($tech_type === 'ecbs') {
            $data['tech'] = 'ecbs';
        } else {
            $data['tech'] = 'ecct';
        }
        return $data;
    }

    public function getDeviceStockApp($tech, $show = 20, $adddata = '', $sort = '') {
        $tech = strtolower($tech);
        $show_key = $tech === 'ecct' ? 'data_view_ecct' : 'data_view_ecbs';
        if ($show < 100 && isset($_GET[$show_key]) && $_GET[$show_key] != "") {
            $show = $_GET[$show_key];
        }
        $sort = "ORDER BY dvc.dvc_priority ASC ";
        $this->load->model("sql_model");
        $filter = $this->sql_model->getFilterEcctList($show);
        if ($show >= 999999) {
            $filter['first'] = 0;
        }
        $result_data = array();
        $sql = "SELECT dvc.id_dvc, dvc.dvc_name, dvc.dvc_code, dvc.dvc_priority FROM inv_dvc dvc WHERE LOWER(dvc.dvc_tech) = '".$tech."' AND UPPER(dvc.dvc_type) = 'APP'";

        if (isset($filter['all'])) {
            $sql .= " " . $filter['all'] . " ";
        }
        $sql .= " " . $sort . " ";
        $count_sql = str_replace("SELECT dvc.id_dvc, dvc.dvc_name, dvc.dvc_code, dvc.dvc_priority", "SELECT COUNT(*) as total", $sql);
        $count_query = $this->db->query($count_sql);
        $total_records = $count_query ? $count_query->row()->total : 0;
        $limit = "LIMIT " . $filter['first'] . ", " . $show . " ";
        $devices_query = $this->db->query($sql . $limit);
        $warna_map = [
            'Blue Navy' => 'Navy',
            'Navy' => 'Navy',
            'Maroon' => 'Maroon',
            'Army' => 'Army',
            'Green Army' => 'Army',
            'Black' => 'Black',
            'Grey' => 'Grey',
            'Gray' => 'Grey',
            'Dark Gray' => 'Dark Gray',
            'Dark Grey' => 'Dark Gray',
            'Custom' => 'Custom',
            '-' => 'Custom',
            '' => 'Custom',
        ];
        if ($devices_query && $devices_query->num_rows() > 0) {
            foreach ($devices_query->result_array() as $device) {
                if ($tech === 'ecbs') {
                    // Ambil semua warna unik
                    $warna_sql = "SELECT DISTINCT dvc_col FROM inv_act WHERE id_dvc = ? AND (inv_out IS NULL OR inv_out = '' OR inv_out = '0000-00-00 00:00:00')";
                    $warna_query = $this->db->query($warna_sql, array($device['id_dvc']));
                    $warna_list = $warna_query && $warna_query->num_rows() > 0 ? $warna_query->result_array() : [['dvc_col' => '-']];
                    foreach ($warna_list as $warna_row) {
                        $warna_raw = $warna_row['dvc_col'] ? $warna_row['dvc_col'] : '-';
                        $warna = isset($warna_map[$warna_raw]) ? $warna_map[$warna_raw] : $warna_raw;
                        $device_data = $device;
                        $device_data['warna'] = $warna;
                        $sizes = array('XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', 'ALL', 'Cus');
                        $total = 0;
                        foreach ($sizes as $size) {
                            $count_sql = "SELECT COUNT(*) as count FROM inv_act WHERE id_dvc = ? AND dvc_size = ? AND dvc_col = ? AND (inv_out IS NULL OR inv_out = '' OR inv_out = '0000-00-00 00:00:00')";
                            $count_query = $this->db->query($count_sql, array($device['id_dvc'], $size, $warna_raw));
                            $count = $count_query ? $count_query->row()->count : 0;
                            $device_data['size_' . strtolower($size)] = $count;
                            $total += $count;
                        }
                        $device_data['subtotal'] = $total;
                        $result_data[] = $device_data;
                    }
                } else {
                    $device_data = $device;
                    $sizes = array('XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', 'ALL', 'Cus');
                    $total = 0;
                    foreach ($sizes as $size) {
                        $count_sql = "SELECT COUNT(*) as count FROM inv_act WHERE id_dvc = ? AND dvc_size = ? AND (inv_out IS NULL OR inv_out = '' OR inv_out = '0000-00-00 00:00:00')";
                        $count_query = $this->db->query($count_sql, array($device['id_dvc'], $size));
                        $count = $count_query ? $count_query->row()->count : 0;
                        $device_data['size_' . strtolower($size)] = $count;
                        $total += $count;
                    }
                    $device_data['subtotal'] = $total;
                    $result_data[] = $device_data;
                } 
            }
        }
        $data['data'] = $result_data;
        $data['page']['sum'] = $total_records;
        $data['page']['show'] = $show;
        $data['page']['first'] = $filter['first'];
        return $data;
    }

    public function getDeviceStockOsc($tech, $show = 20, $adddata = '', $sort = '') {
        $tech = strtolower($tech);
        $show_key = $tech === 'ecct' ? 'data_view_ecct' : 'data_view_ecbs';
        if ($show < 100 && isset($_GET[$show_key]) && $_GET[$show_key] != "") {
            $show = $_GET[$show_key];
        }
        $sort = "ORDER BY dvc.id_dvc ASC ";
        $this->load->model("sql_model");
        $filter = $this->sql_model->getFilterEcctList($show);
        if ($show >= 999999) {
            $filter['first'] = 0;
        }
        $sql = "SELECT dvc.id_dvc, dvc.dvc_name, dvc.dvc_code, dvc.dvc_tech, dvc.dvc_type, " .
            "(SELECT COUNT(*) FROM inv_act WHERE id_dvc = dvc.id_dvc AND (inv_out IS NULL OR inv_out = '' OR inv_out = '0000-00-00 00:00:00') AND dvc_qc = 'LN') as ln_count, " .
            "(SELECT COUNT(*) FROM inv_act WHERE id_dvc = dvc.id_dvc AND (inv_out IS NULL OR inv_out = '' OR inv_out = '0000-00-00 00:00:00') AND dvc_qc = 'DN') as dn_count " .
            "FROM inv_dvc dvc WHERE LOWER(dvc.dvc_tech) = '".$tech."' ".
            "AND dvc.status = 0 ".
            "AND UPPER(dvc.dvc_type) = 'OSC' ";

        if (isset($filter['all'])) {
            $sql .= " " . $filter['all'] . " ";
        }
        $sql .= " " . $sort . " ";
        $count_sql = "SELECT COUNT(*) as total FROM inv_dvc dvc WHERE LOWER(dvc.dvc_tech) = '".$tech."' ".
                    "AND dvc.status = 0".
                    "AND UPPER(dvc.dvc_type) = 'OSC'";
        $count_query = $this->db->query($count_sql);
        $total_records = $count_query ? $count_query->row()->total : 0;
        $limit = "LIMIT " . $filter['first'] . ", " . $show . " ";
        $main_query = $this->db->query($sql . $limit);
        $data['query'] = $main_query;
        $data['page']['sum'] = $total_records;
        $data['page']['show'] = $show;
        $data['page']['first'] = $filter['first'];
        return $data;
    }

    public function getDeviceTypes($dvc_tech = '') {
        $this->db->select('dvc_code');
        $this->db->distinct();
        $this->db->where('status', 0);
        
        if (!empty($dvc_tech)) {
            $this->db->where('dvc_tech', $dvc_tech);
        }
        
        $this->db->where('dvc_code IS NOT NULL');
        $this->db->where('dvc_code !=', '');
        $this->db->order_by('dvc_code', 'ASC');
        
        $query = $this->db->get('inv_dvc');
        return $query->result();
    }

    public function getCustom($col, $tab, $key, $val) {
        $sql = "SELECT " . $col . " ";
        $sql .= "FROM " . $tab . " ";
        $sql .= "WHERE " . $key . "='" . $val . "' ";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        if ($col == "*") {
            return $data;
        } else {
            return $data[$col];
        }
    }

    public function processInventoryInput($data) {
        try {
            // Validate input data
            if (!isset($data['type']) || !in_array($data['type'], ['in', 'out', 'move'])) {
                return false;
            }
            
            // Process based on type
            switch ($data['type']) {
                case 'in':
                    return $this->processInventoryIn($data);
                case 'out':
                    return $this->processInventoryOut($data);
                case 'move':
                    return $this->processInventoryMove($data);
                default:
                    return false;
            }
        } catch (Exception $e) {
            log_message('error', 'Process inventory input error: ' . $e->getMessage());
            return false;
        }
    }
    
    private function processInventoryIn($data) {
        $insert_data = array(
            'id_dvc' => $data['id_dvc'],
            'dvc_sn' => isset($data['dvc_sn']) ? $data['dvc_sn'] : '',
            'dvc_size' => isset($data['dvc_size']) ? $data['dvc_size'] : '',
            'dvc_col' => isset($data['dvc_col']) ? $data['dvc_col'] : '',
            'dvc_qc' => isset($data['dvc_qc']) ? $data['dvc_qc'] : '',
            'inv_in' => date('Y-m-d H:i:s'),
            'adm_in' => isset($data['quantity']) ? $data['quantity'] : 1
        );
        
        return $this->db->insert('inv_act', $insert_data);
    }
    
    private function processInventoryOut($data) {
        $insert_data = array(
            'id_dvc' => $data['id_dvc'],
            'dvc_sn' => isset($data['dvc_sn']) ? $data['dvc_sn'] : '',
            'dvc_size' => isset($data['dvc_size']) ? $data['dvc_size'] : '',
            'dvc_col' => isset($data['dvc_col']) ? $data['dvc_col'] : '',
            'dvc_qc' => isset($data['dvc_qc']) ? $data['dvc_qc'] : '',
            'inv_out' => date('Y-m-d H:i:s'),
            'adm_out' => isset($data['quantity']) ? $data['quantity'] : 1
        );
        
        return $this->db->insert('inv_act', $insert_data);
    }
    
    private function processInventoryMove($data) {
        $insert_data = array(
            'id_dvc' => $data['id_dvc'],
            'dvc_sn' => isset($data['dvc_sn']) ? $data['dvc_sn'] : '',
            'dvc_size' => isset($data['dvc_size']) ? $data['dvc_size'] : '',
            'dvc_col' => isset($data['dvc_col']) ? $data['dvc_col'] : '',
            'dvc_qc' => isset($data['dvc_qc']) ? $data['dvc_qc'] : '',
            'inv_move' => date('Y-m-d H:i:s'),
            'adm_move' => isset($data['quantity']) ? $data['quantity'] : 1,
            'loc_move' => isset($data['location']) ? $data['location'] : ''
        );
        
        return $this->db->insert('inv_act', $insert_data);
    }
}
