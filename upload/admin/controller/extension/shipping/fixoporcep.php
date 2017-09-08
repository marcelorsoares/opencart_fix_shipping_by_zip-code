<?php
error_reporting(E_ALL);
class ControllerExtensionShippingFixoporcep extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/shipping/fixoporcep');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('fixoporcep', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/shipping/fixoporcep', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_none'] = $this->language->get('text_none');

        $data['text_first_zip'] = $this->language->get('text_first_zip');
        $data['text_last_zip'] = $this->language->get('text_last_zip');

        $data['entry_cost'] = $this->language->get('entry_cost');
        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['entry_first_zip'] = $this->language->get('entry_first_zip');
        $data['entry_last_zip'] = $this->language->get('entry_last_zip');

        $data['entry_total'] = $this->language->get('entry_total');
        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_shipping'),
            'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/fixoporcep', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('extension/shipping/fixoporcep', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['fixoporcep_total'])) {
            $data['fixoporcep_total'] = $this->request->post['fixoporcep_total'];
        } else {
            $data['fixoporcep_total'] = $this->config->get('fixoporcep_total');
        }

        if (isset($this->request->post['fixoporcep_cost'])) {
            $data['fixoporcep_cost'] = $this->request->post['fixoporcep_cost'];
        } else {
            $data['fixoporcep_cost'] = $this->config->get('fixoporcep_cost');
        }

        if (isset($this->request->post['fixoporcep_tax_class_id'])) {
            $data['fixoporcep_tax_class_id'] = $this->request->post['fixoporcep_tax_class_id'];
        } else {
            $data['fixoporcep_tax_class_id'] = $this->config->get('fixoporcep_tax_class_id');
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['fixoporcep_geo_zone_id'])) {
            $data['fixoporcep_geo_zone_id'] = $this->request->post['fixoporcep_geo_zone_id'];
        } else {
            $data['fixoporcep_geo_zone_id'] = $this->config->get('fixoporcep_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['fixoporcep_status'])) {
            $data['fixoporcep_status'] = $this->request->post['fixoporcep_status'];
        } else {
            $data['fixoporcep_status'] = $this->config->get('fixoporcep_status');
        }

        if (isset($this->request->post['fixoporcep_sort_order'])) {
            $data['fixoporcep_sort_order'] = $this->request->post['fixoporcep_sort_order'];
        } else {
            $data['fixoporcep_sort_order'] = $this->config->get('fixoporcep_sort_order');
        }

        if (isset($this->request->post['fixoporcep_first_zip'])) {
            $data['fixoporcep_first_zip'] = $this->request->post['fixoporcep_first_zip'];
        } else {
            $data['fixoporcep_first_zip'] = $this->config->get('fixoporcep_first_zip');
        }

        if (isset($this->request->post['fixoporcep_last_zip'])) {
            $data['fixoporcep_first_zip'] = $this->request->post['fixoporcep_last_zip'];
        } else {
            $data['fixoporcep_last_zip'] = $this->config->get('fixoporcep_last_zip');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/fixoporcep.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/fixoporcep')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
?>