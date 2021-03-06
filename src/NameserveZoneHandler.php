<?php
/**
 * Created by PhpStorm.
 * User: jpwal
 * Date: 22.09.2016
 * Time: 01:40.
 */
namespace LumaservSystems;

class NameserveZoneHandler
{
    private $domainhandler;
    private $lumaserv;

    public function __construct(DomainHandler $domainhandler, LUMASERV $lumaserv)
    {
        $this->domainhandler = $domainhandler;
        $this->lumaserv = $lumaserv;
    }

    /**
     * @return array|string
     */
    public function getAll()
    {
        return $this->lumaserv->get('domains/zones');
    }

    /**
     * search for special nameserverzone
     * you can pass some filter fields to get only special zones
     * if you leave all fields empty, you will get all zones.
     *
     * @param string $filter_title     basic sql search parameters like %, _ etc. are possible
     * @param string $filter_ttl       basic sql search parameters like %, _ etc. are possible
     * @param string $filter_first_ns  basic sql search parameters like %, _ etc. are possible
     * @param string $filter_second_ns basic sql search parameters like %, _ etc. are possible
     * @param string $filter_username  basic sql search parameters like %, _ etc. are possible
     *
     * @return array|string
     */
    public function searchZone($filter_title = '', $filter_ttl = '', $filter_first_ns = '', $filter_second_ns = '', $filter_username = '')
    {
        return $this->lumaserv->get('domains/zones', [
            'filter_title'     => $filter_title,
            'filter_ttl'       => $filter_ttl,
            'filter_first_ns'  => $filter_first_ns,
            'filter_second_ns' => $filter_second_ns,
            'filter_username'  => $filter_username,
        ]);
    }

    /**
     * create a new nameserver zone.
     *
     * @param string $title   title of the new zone
     * @param int    $ttl     ttl of the zone
     * @param array  $records array of the new records (sld, ttl, type, data)
     * @param string $ns_1    string of nameserver subdomain, default: ns1.lumaserv.eu
     * @param string $ns_2    string of nameserver subdomain, default: ns2.lumaserv.eu
     *
     * @return array|string
     */
    public function create($title, $ttl, $records = [], $ns_1 = 'ns1.lumaserv.eu', $ns_2 = 'ns2.lumaserv.eu')
    {
        return $this->lumaserv->post('domains/zones/create', [
            'title'   => $title,
            'ttl'     => $ttl,
            'ns_1'    => $ns_1,
            'ns_2'    => $ns_2,
            'user_id' => null, //currently not impleneted in this client
            'records' => $records,
        ]);
    }

    public function edit($zone_id, $title, $ttl, $ns_1 = null, $ns_2 = null)
    {
        return $this->lumaserv->put('domains/zones/'.$zone_id, [
            'title'   => $title,
            'ttl'     => $ttl,
            'ns_1'    => $ns_1,
            'ns_2'    => $ns_2,
            'user_id' => null, //currently not impleneted in this client
        ]);
    }

    public function detail($zone_id)
    {
        return $this->lumaserv->get('domains/zones/'.$zone_id);
    }

    public function addEntry($zone_id, $sld, $ttl, $type, $data)
    {
        return $this->addEntries($zone_id, [
            [
                'sld'  => $sld,
                'ttl'  => $ttl,
                'type' => $type,
                'data' => $data
            ],
        ]);
    }

    public function addEntries($zone_id, $records)
    {
        return $this->lumaserv->put('domains/zones/'.$zone_id.'/entries', [
            'records' => $records,
        ]);
    }

    public function changeEntries($zone_id, $old_record_sld = null, $old_record_ttl = null, $old_record_type = null, $old_record_data = null, $record_sld = null, $record_ttl = null, $record_type = null, $record_data = null, $limit = null)
    {
        return $this->lumaserv->post('domains/zones/'.$zone_id.'/changeEntries', [
            'old_record' => [
                'sld' => $old_record_sld,
                'ttl' => $old_record_ttl,
                'type' => $old_record_type,
                'data' => $old_record_data
            ],
            'record' => [
                'sld' => $record_sld,
                'ttl' => $record_ttl,
                'type' => $record_type,
                'data' => $record_data
            ],
            'limit' => $limit
        ]);
    }

    public function delEntry($zone_id, $sld, $ttl = null, $type = null, $data = null, $limit = null)
    {
        return $this->delEntries($zone_id, [
            [
                'sld'  => $sld,
                'ttl'  => $ttl,
                'type' => $type,
                'data' => $data,
                'limit' => $limit,
            ],
        ]);
    }

    public function delEntries($zone_id, $records)
    {
        return $this->lumaserv->delete('domains/zones/'.$zone_id.'/entries', [
            'records' => $records,
        ]);
    }
}
