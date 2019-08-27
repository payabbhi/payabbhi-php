<?php
namespace Payabbhi;

/**
 * Class Event
 *
 * @property string $id
 * @property string $object
 * @property string $type
 * @property string $environment
 * @property mixed $data
 * @property int $created_at
 *
 * @package Payabbhi
 */


Class Event extends ApiResource
{
  /**
   * @param string $id The ID of the event to retrieve.
   *
   * @return Event
   */
  public function retrieve($id)
  {
      return $this->_retrieve($id);
  }

  /**
   * @param array|null $params
   *
   * @return Collection of Events
   */
  public function all($params = null)
  {
      return $this->_all($params);

  }
}
