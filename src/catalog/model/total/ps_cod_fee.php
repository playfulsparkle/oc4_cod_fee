<?php
namespace Opencart\Catalog\Model\Extension\PSCodFee\Total;
/**
 * Class PSCodFee
 *
 * @package Opencart\Catalog\Model\Extension\PSCodFee\Total
 */
class PSCodFee extends \Opencart\System\Engine\Model
{
    /**
     * @param array $totals
     * @param array $taxes
     * @param float $total
     *
     * @return void
     */
    public function getTotal(array &$totals, array &$taxes, float &$total): void
    {
        if (
            (float) $this->config->get('total_ps_cod_fee_fee') > 0 &&
            isset($this->session->data['payment_method']) &&
            $this->session->data['payment_method']['code'] === 'cod.cod' &&
            $this->cart->getSubTotal() > 0
        ) {
            $this->load->language('extension/ps_cod_fee/total/ps_cod_fee');

            $totals[] = [
                'extension' => 'ps_cod_fee',
                'code' => 'ps_cod_fee',
                'title' => $this->language->get('text_ps_cod_fee'),
                'value' => (float) $this->config->get('total_ps_cod_fee_fee'),
                'sort_order' => (int) $this->config->get('total_ps_cod_fee_sort_order')
            ];

            if ((int) $this->config->get('total_ps_cod_fee_tax_class_id')) {
                $tax_rates = $this->tax->getRates((float) $this->config->get('total_ps_cod_fee_fee'), (int) $this->config->get('total_ps_cod_fee_tax_class_id'));

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($taxes[$tax_rate['tax_rate_id']])) {
                        $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                    } else {
                        $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                    }
                }
            }

            $total += (float) $this->config->get('total_ps_cod_fee_fee');
        }
    }
}
