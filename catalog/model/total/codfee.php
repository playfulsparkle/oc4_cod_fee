<?php
namespace Opencart\Catalog\Model\Extension\CodFee\Total;
/**
 * Class CodFee
 *
 * @package Opencart\Catalog\Model\Extension\CodFee\Total
 */
class CodFee extends \Opencart\System\Engine\Model {
	/**
	 * @param array $totals
	 * @param array $taxes
	 * @param float $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if ($this->cart->getSubTotal() > 0 && isset($this->session->data['payment_method']) && $this->session->data['payment_method']['code'] === 'cod.cod') {
			$this->load->language('extension/codfee/total/codfee');

			$totals[] = [
				'extension'  => 'codfee',
				'code'       => 'codfee',
				'title'      => $this->language->get('text_codfee'),
				'value'      => (float)$this->config->get('total_codfee_fee'),
				'sort_order' => (int)$this->config->get('total_codfee_sort_order')
			];

			if ($this->config->get('total_codfee_tax_class_id')) {
				$tax_rates = $this->tax->getRates((float)$this->config->get('total_codfee_fee'), (int)$this->config->get('total_codfee_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += (float)$this->config->get('total_codfee_fee');
		}
	}
}
