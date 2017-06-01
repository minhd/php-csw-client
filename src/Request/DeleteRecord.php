<?php


namespace MinhD\CSWClient\Request;

class DeleteRecord extends DeleteRecords
{
    public function __construct($dcID)
    {
        $constraints =
'<csw:Constraint version="2.0.0">
  <ogc:Filter>
    <ogc:PropertyIsEqualTo>
        <ogc:PropertyName>dc:identifier</ogc:PropertyName>
        <ogc:Literal>'.$dcID.'</ogc:Literal>
    </ogc:PropertyIsEqualTo>
  </ogc:Filter>
</csw:Constraint>';
        parent::__construct($constraints);
    }
}