<?php
namespace Opencart\Admin\Controller\Extension\PSCodFee\Total;
/**
 * Class PSCodFee
 *
 * @package Opencart\Admin\Controller\Extension\PSCodFee\Total
 */
class PSCodFee extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/ps_cod_fee/total/ps_cod_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ps_cod_fee/total/ps_cod_fee', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/ps_cod_fee/total/ps_cod_fee.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total');

		$data['total_ps_cod_fee_fee'] = $this->config->get('total_ps_cod_fee_fee');
		$data['total_ps_cod_fee_tax_class_id'] = $this->config->get('total_ps_cod_fee_tax_class_id');

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$data['total_ps_cod_fee_status'] = $this->config->get('total_ps_cod_fee_status');
		$data['total_ps_cod_fee_sort_order'] = $this->config->get('total_ps_cod_fee_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/ps_cod_fee/total/ps_cod_fee', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/ps_cod_fee/total/ps_cod_fee');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/ps_cod_fee/total/ps_cod_fee')) {
			$json['error'] = $this->language->get('error_permission');
		}

        if (isset($this->request->post['total_ps_cod_fee_status']) && $this->request->post['total_ps_cod_fee_status'] === '1') {
            if ((float)$this->request->post['total_ps_cod_fee_fee'] <= 0) {
                $json['error']['fee'] = $this->language->get('error_total_ps_cod_fee_fee');
            }
        }

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('total_ps_cod_fee', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
