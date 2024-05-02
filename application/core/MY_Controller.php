<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    protected $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        /*echo $this->router->fetch_class();
        echo $this->router->fetch_method();
        die;*/

        if ($this->router->fetch_class() != "api" && !$this->ion_auth->logged_in()) {
            $get = $this->input->get(NULL, TRUE);
            redirect('login?'.http_build_query($get), 'refresh');
        }

        ini_set('memory_limit', '6144M');
        ini_set("date.timezone", "America/Sao_Paulo");
    }

    public function _render_view($page = '')
    {
        /*$notification_count = $this->getNotificationCount();
        $this->data['notifications_count'] = ($notification_count > 99 ? "99+" : $notification_count);
        $this->data['notifications_10'] = $this->get10Notifications();*/
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['version'] = $this->config->item('version');
        $this->load->view($page, $this->data);
    }

    /* START UTILS CONTROLLER */

    public function returnJson($array, $statusCode)
    {
        if (is_array($array)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($array);
            $this->output->set_status_header($statusCode);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo 'Array inválido.';
            $this->output->set_status_header('500');
        }
    }

    /* END UTILS CONTROLLER */

    /* NOTIFICAÇÕES */

    public function notifyUser($model, $user_id, $menssage, $href = null, $ref_1 = null, $ref_2 = null)
    {
        if (empty($model) || empty($user_id) || empty($model)) {
            return false;
        }
        $data = array(
            'user_id' => $user_id,
            'mensagem' => $menssage,
            'href' => ($href == null ? "" : $href),
            'ref_1' => ($ref_1 == null ? "" : $ref_1),
            'ref_2' => ($ref_2 == null ? "" : $ref_2)
        );
        return $this->db->insert('notificacoes', $data);
    }

    public function getNotificationCount()
    {
        $where = 'user_id = "' . $this->ion_auth->user()->row()->id . '"';
        return $this->db->where("user_id", $this->ion_auth->user()->row()->id)
            ->where("visualizado", '0')
            ->order_by("id", "ASC")
            ->count_all_results("notificacoes");
    }

    public function get10Notifications()
    {
        $query = "SELECT
                    id,
                    mensagem,
                    visualizado,
                    DATE_FORMAT(criado_em,'%e/%c/%y %H:%i') AS criado_em
                  FROM notificacoes
                  WHERE user_id = " . $this->ion_auth->user()->row()->id . "
                  ORDER BY id desc
                  LIMIT 10";
        return $this->db->query($query)->result();
    }
}
