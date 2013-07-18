  <div class="form-inline">
  {foreach from=$beginHookFormElements key=dontCare item=hookFormElement}
    <label>{$form.$hookFormElement.label}</label>{$form.$hookFormElement.html}
  {/foreach}
  </div>
