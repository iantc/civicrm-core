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
{* this template is used for displaying event information *}
{foreach from=$viewCustomData item=customValues key=customGroupId}
  {foreach from=$customValues item=cd_edit key=cvID}
    {foreach from=$cd_edit.fields item=element key=field_id}
      {if $element.field_title=="SubTitle"}
        {assign var=SubTitle value=$element.field_value}
      {/if}
      {if $element.field_title=='Instructor(s)'}
        {assign var=Instructors value=$element.field_value}
      {/if}
      {if substr_count($element.field_title,'LSSC') > 0}
        {assign var=lssc value=$element.field_value}
      {/if}
      {if substr_count($element.field_title,'Series') > 0}
        {assign var=seriesInfo value=$element.field_value}
      {/if}
      {if substr_count($element.field_title,'Information') > 0}
        {assign var=moreInfo value=$element.field_value}
      {/if}
    {/foreach}
  {/foreach}
{/foreach}
<div class="subtitle-wrapper">
  <h2>{$SubTitle}</h2>
</div>
{if $allowRegistration}
  <div class="action-link section register_link-section register_link-top">
    <a href="{$registerURL}" title="{$registerText}" class="btn btn-info crm-register-button"><span>{$registerText}</span></a>
  </div>
{/if}
{if $registerClosed }
<div class="messages status alert alert-info pull-left">
  <div class="icon icon-info-sign"></div>
     &nbsp;{ts}Registration is closed for this event{/ts}
  </div>
{/if}
{if call_user_func(array('CRM_Core_Permission','check'), 'access CiviEvent')}
  <div class="crm-actions-ribbon crm-event-manage-tab-actions-ribbon btn-group">
  	<ul id="actions">
      {if call_user_func(array('CRM_Core_Permission','check'), 'edit all events')}
      	<li>
          <div id="crm-event-links-wrapper">
    	      <div id="crm-event-configure-link" class="btn"><span title="{ts}Configure this event.{/ts}"><div class="icon icon-wrench"></div></span></div><!-- change icon class to work with Font Awesome - ic - 2013-05-04 -->
      	      <div class="dropdown-menu" id="crm-event-links-list">
  	      	    <div class="crm-event-links-list-inner">
    	      	   	<ul>
            		    <li><a title="{ts}Info and Settings{/ts}" href="{crmURL p='civicrm/event/manage/settings' q="reset=1&action=update&id=`$event.id`"}">{ts}Info and Settings{/ts}</a></li>
            		    <li><a title="{ts}Location{/ts}" href="{crmURL p='civicrm/event/manage/location' q="reset=1&action=update&id=`$event.id`"}">{ts}Location{/ts}</a></li>
            		    <li><a title="{ts}Fees{/ts}" href="{crmURL p='civicrm/event/manage/fee' q="reset=1&action=update&id=`$event.id`"}">{ts}Fees{/ts}</a></li>
            		    <li><a title="{ts}Online Registration{/ts}" href="{crmURL p='civicrm/event/manage/registration' q="reset=1&action=update&id=`$event.id`"}">{ts}Online Registration{/ts}</a></li>
            		    <li><a title="{ts}Schedule Reminders{/ts}" href="{crmURL p='civicrm/event/manage/reminder' q="reset=1&action=update&id=`$event.id`"}">{ts}Schedule Reminders{/ts}</a></li>
                    {if $eventCartEnabled}
                      <li><a title="{ts}Conference Slots{/ts}" href="{crmURL p='civicrm/event/manage/conference' q="reset=1&action=update&id=`$event.id`"}">{ts}Conference Slots{/ts}</a></li>
                    {/if}
                    <li><a title="{ts}Tell a Friend{/ts}" href="{crmURL p='civicrm/event/manage/friend' q="reset=1&action=update&id=`$event.id`"}">{ts}Tell a Friend{/ts}</a></li>
                    <li><a title="{ts}Personal Campaign Pages{/ts}" href="{crmURL p='civicrm/event/manage/pcp' q="reset=1&action=update&id=`$event.id`"}">{ts}Personal Campaign Pages{/ts}</a></li>
                  </ul>
  	           </div>
    	      </div>
          </div>
        </li>
      {/if}
    <li>
      <div id="crm-participant-wrapper">
        <div id="crm-participant-links" class="btn"><span title="{ts}Participant listing links.{/ts}"><div class="icon icon-search"></div></div><!-- change icon class to work with Font Awesome - ic - 2013-05-04 -->
  	      <div class="dropdown-menu" id="crm-participant-list">
      	   <div class="crm-participant-list-inner">
	      	   	<ul>
                {if $findParticipants.statusCounted}
    		    	    <li><a class="crm-participant-counted" href="{crmURL p='civicrm/event/search' q="reset=1&force=1&event=`$event.id`&status=true"}">{$findParticipants.statusCounted|replace:'/':', '}</a></li>
  			        {/if}

		            {if $findParticipants.statusNotCounted}
      			    	<li><a class="crm-participant-not-counted" href="{crmURL p='civicrm/event/search' q="reset=1&force=1&event=`$event.id`&status=false"}">{$findParticipants.statusNotCounted|replace:'/':', '}</a>
      				    </li>
  			        {/if}
		            {if $participantListingURL}
  			    	    <li><a class="crm-participant-listing" href="{$participantListingURL}">{ts}Public Participant Listing{/ts}</a></li>
  			        {/if}
  		        </ul>
             </div>
  	      </div>
      	</div>
      </li>
  	</ul>
  </div>
{/if}


<div id="crm-event-info-wrapper"> <!-- insert extra wrapper for theming - ic 2013-05-04 -->
  <div class="vevent crm-block crm-event-info-form-block">
    <div id="event-specifics-wrapper">
      <div class="crm-section event_date_time-section">
        <div class="row event-info-wrapper"><!-- add .row wrapper and add span# classes for layout -->
          <div class="span3 event-info-label"><label>{ts}When{/ts}</label></div>
          <div class="span9 event-info-detail">
            <div class="row">
              <div class="span3"><strong>Start:</strong></div>
              <div class="span9"><abbr class="dtstart" title="{$event.event_start_date|crmDate}">
                {$event.event_start_date|crmDate}</abbr>
              </div>
            </div>
            {if $event.event_end_date}
              <div class="row">
                <div class="span3"><strong>End:</strong></div>
                <div class="span9">
                  {* Only show end time if end date = start date *}
                  {if $event.event_end_date|date_format:"%Y%m%d" == $event.event_start_date|date_format:"%Y%m%d"}
                    <abbr class="dtend" title="{$event.event_end_date|crmDate:0:1}">
                    {$event.event_end_date|crmDate:0:1}
                    </abbr>
                  {else}
                    <abbr class="dtend" title="{$event.event_end_date|crmDate}">
                    {$event.event_end_date|crmDate}
                    </abbr>
                  {/if}
                </div>
              </div>
            {/if}
            { if $event.is_public }
              <div class="ical-links-top">
                {include file="CRM/Event/Page/iCalLinks.tpl"}
              </div>
            {/if}
          </div>
        </div>
      </div>
      {if $event.is_monetary eq 1 && $feeBlock.value}
        <div class="crm-section event_fees-section">
          <div class="row event-info-wrapper"><!-- add .row wrapper and add span# classes for layout -->
            <div class="span3 event-info-label"><label>{$event.fee_label}</label></div>
            <div class="span9 event-info-detail">
              {foreach from=$feeBlock.value name=fees item=value}
                {assign var=idx value=$smarty.foreach.fees.iteration}
                {* Skip price field label for quick_config price sets since it duplicates $event.fee_label *}
                {if $feeBlock.lClass.$idx}
                  {assign var="lClass" value=$feeBlock.lClass.$idx}
                {else}
                  {assign var="lClass" value="fee_level-label"}
                {/if}
                {if $isQuickConfig && $lClass EQ "price_set_option_group-label"}
                  {* Skip price field label for quick_config price sets since it duplicates $event.fee_label *}
                {else}
                  <div class="row">
                    <div class="{$lClass} span3"><strong>{$feeBlock.label.$idx}</strong></div>
                    {if $isPriceSet & $feeBlock.isDisplayAmount.$idx}
                      <div class="fee_amount-value span9">{$feeBlock.value.$idx|crmMoney}</div>
                    {/if}
                  </div>
                {/if}
              {/foreach}
            </div>
          </div>
        </div>
      {/if}
    	<div class="event-info">
        <div class="event-instructor">Instructor(s): {$Instructors}</div>
      	{if $event.summary}
    	    <div class="crm-section event_summary-section">
            {$event.summary}
    	    </div>
      	{/if}
      	{if $event.description}
    	    <div class="crm-section event_description-section summary">
            {$event.description}
    	    </div>
      	{/if}
      	{if $isShowLocation}
          {if $location.address.1}
            <div class="crm-section event_address-section">
              <div class="row event-info-wrapper"><!-- add .row wrapper and add span# classes for layout -->
                <div class="span3 event-info-label"><label>{ts}Location{/ts}</label></div>
                <div class="span9 event-info-detail">{$location.address.1.display|nl2br}</div>
              </div>
            </div>
          {/if}
    	    {if ( $event.is_map && $config->mapProvider &&
            ( is_numeric($location.address.1.geo_code_1)  ||
            ( $config->mapGeoCoding && $location.address.1.city AND $location.address.1.state_province ) ) ) }
            <div class="crm-section event_map-section">
              <div class="content">
                {assign var=showDirectly value="1"}
                {include file="CRM/Contact/Form/Task/Map/`$config->mapProvider`.tpl" fields=$showDirectly}
                <br /><a href="{$mapURL}" title="{ts}Show large map{/ts}">{ts}Show large map{/ts}</a>
              </div>
            </div>
    	    {/if}
      	{/if}{*End of isShowLocation condition*}
      	{if $location.phone.1.phone || $location.email.1.email}
    	    <div class="crm-section event_contact-section">
            <div class="row event-info-wrapper"><!-- add .row wrapper and add span# classes for layout -->
              <div class="span3 event-info-label"><label>{ts}Contact{/ts}</label></div>
    	        <div class="span9 event-info-detail">
                {* loop on any phones and emails for this event *}
                {foreach from=$location.phone item=phone}
                  {if $phone.phone}
                    {if $phone.phone_type}{$phone.phone_type_display}{else}{ts}Phone{/ts}{/if}:
                    <span class="tel">{$phone.phone}</span> <br />
                  {/if}
                {/foreach}
                {foreach from=$location.email item=email}
                  {if $email.email}
                    {ts}Email:{/ts} <span class="email"><a href="mailto:{$email.email}">{$email.email}</a></span>
                  {/if}
                {/foreach}
    	        </div>
            </div>
    	    </div>
      	{/if}
        <div class="more-info-wrapper">
          <div>LSSC Approved: {$lssc}</div>
          {if $seriesInfo!=''}
            <div>Series Info: {$seriesInfo}</div>
          {/if}
          {if $moreInfo!=''}
            <div>Series Info: {$moreInfo}</div>
          {/if}
        </div>
      </div>
    	{if $allowRegistration}
        <div class="action-link section register_link-section register_link-bottom">
          <a href="{$registerURL}" title="{$registerText}" class="btn btn-info crm-register-button"><span>{$registerText}</span></a>
        </div>
      {/if}
      {if $event.is_share }
        {capture assign=eventUrl}{crmURL p='civicrm/event/info' q="id=`$event.id`&amp;reset=1" a=true fe=1 h=1}{/capture}
        {include file="CRM/common/SocialNetwork.tpl" url=$eventUrl title=$event.title pageURL=$eventUrl}
      {/if}
    </div>
  </div>
</div><!-- close info wrapper - ic -2013-05-14 -->
{literal}
<script type="text/javascript">

cj('body').click(function() {
    cj('#crm-event-links-list').hide();
    cj('#crm-participant-list').hide();
});

cj('#crm-event-configure-link').click(function(event) {
    cj('#crm-event-links-list').toggle();
    cj('#crm-participant-list').hide();
    event.stopPropagation();
});

cj('#crm-participant-links').click(function(event) {
    cj('#crm-participant-list').toggle();
    cj('#crm-event-links-list').hide();
    event.stopPropagation();
});

</script>
{/literal}
