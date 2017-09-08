<?php
class ModelShippingFixoporcep extends Model {
    function getQuote($address) {
        $this->load->language('shipping/fixoporcep');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('fixoporcep_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if (!$this->config->get('fixoporcep_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        if ($this->cart->getSubTotal() < $this->config->get('fixoporcep_total')) {
            $status = false;
        }

        // print $this->cart->getSubTotal();

        $zip = str_replace("-","",$address['postcode']);
        $first_zip = $this->config->get('fixoporcep_first_zip');
        $last_zip = $this->config->get('fixoporcep_last_zip');


        if( ( (int)$zip >= (int)$first_zip ) && ( (int)$zip <= (int)$last_zip ) && ($this->cart->getSubTotal() >= $this->config->get('fixoporcep_total')) ){
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $quote_data = array();

            $quote_data['fixoporcep'] = array(
                'code'         => 'fixoporcep.fixoporcep',
                'title'        => $this->language->get('text_description'),
                'cost'         => $this->config->get('fixoporcep_cost'),
                'tax_class_id' => $this->config->get('fixoporcep_tax_class_id'),
                'text'         => $this->currency->format($this->tax->calculate($this->config->get('fixoporcep_cost'), $this->config->get('fixoporcep_tax_class_id'), $this->config->get('config_tax')))
            );

            $method_data = array(
                'code'       => 'fixoporcep',
                'title'      => $this->language->get('text_title'),
                'quote'      => $quote_data,
                'sort_order' => $this->config->get('fixoporcep_sort_order'),
                'error'      => false
            );
        }

        return $method_data;
    }
}