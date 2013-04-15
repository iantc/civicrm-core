{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}

<div class="crm-mailing-selector">
  <table id="contact-mailing-selector">
    <thead>
    <tr>
      <th class='crm-mailing-contact-subject'>{ts}Subject{/ts}</th>
      <th class='crm-mailing-contact_created'>{ts}Created By{/ts}</th>
      <th class='crm-mailing-contact-date'>{ts}Date{/ts}</th>
      <th class='crm-mailing-contact-links nosort'>&nbsp;</th>
      <th class='hiddenElement'>&nbsp;</th>
    </tr>
    </thead>
  </table>
</div>
{literal}
<script type="text/javascript">
  var oTable;

  cj(function ( ) {
    buildMailingContact( );
  });

function buildMailingContact() {
  oTable.fnDestroy();

  var columns = '';
  var sourceUrl = {/literal}'{crmURL p="civicrm/ajax/rest" h=0 q="entity=MailingContact&action=get&sequential=1&json=1&contact_id=$contactId"}'{literal};

  var ZeroRecordText = {/literal}'{ts escape="js"}No mailings found{/ts}.'{literal};

  oTable = cj('#mailing-contact-selector').dataTable({
    "bFilter"    : false,
    "bAutoWidth" : false,
    "aaSorting"  : [],
    "aoColumns"  : [
      {sClass:'crm-mailing-contact-subject'},
      {sClass:'crm-mailing-contact_created'},
      {sClass:'crm-mailing-contact-date'},
      {sClass:'crm-mailing-contact-links', bSortable:false},
      {sClass:'hiddenElement', bSortable:false}
    ],
    "bProcessing": true,
    "sPaginationType": "full_numbers",
    "sDom"       : '<"crm-datatable-pager-top"lfp>rt<"crm-datatable-pager-bottom"ip>',
    "bServerSide": true,
    "bJQueryUI": true,
    "sAjaxSource": sourceUrl,
    "iDisplayLength": 25,
    "oLanguage": {
      "sZeroRecords":  ZeroRecordText,
      "sProcessing":   {/literal}"{ts escape='js'}Processing...{/ts}"{literal},
      "sLengthMenu":   {/literal}"{ts escape='js'}Show _MENU_ entries{/ts}"{literal},
      "sInfo":         {/literal}"{ts escape='js'}Showing _START_ to _END_ of _TOTAL_ entries{/ts}"{literal},
      "sInfoEmpty":    {/literal}"{ts escape='js'}Showing 0 to 0 of 0 entries{/ts}"{literal},
      "sInfoFiltered": {/literal}"{ts escape='js'}(filtered from _MAX_ total entries){/ts}"{literal},
      "sSearch":       {/literal}"{ts escape='js'}Search:{/ts}"{literal},
      "oPaginate": {
        "sFirst":    {/literal}"{ts escape='js'}First{/ts}"{literal},
        "sPrevious": {/literal}"{ts escape='js'}Previous{/ts}"{literal},
        "sNext":     {/literal}"{ts escape='js'}Next{/ts}"{literal},
        "sLast":     {/literal}"{ts escape='js'}Last{/ts}"{literal}
      }
    },
    "fnDrawCallback": function() { setSelectorClass(); },
    "fnServerData": function ( sSource, aoData, fnCallback ) {
        aoData.push( {name:'contact_id', value: {/literal}{$contactId}{literal}},
      {name:'admin',   value: {/literal}'{$admin}'{literal}}
      );

      cj.ajax( {
        "dataType": 'json',
        "type": "POST",
        "url": sSource,
        "data": aoData,
        "success": fnCallxback,
        // CRM-10244
        "dataFilter": function(data, type) { return data.replace(/[\n\v\t]/g, " "); }
      });
    }
  });
}

function setSelectorClass( ) {
  cj('#contact-mailing-selector' + ' td:last-child').each( function( ) {
    cj(this).parent().addClass(cj(this).text() );
  });
}
</script>
{/literal}
