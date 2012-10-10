<if:leader>
<div style="float: left; width: 350px;">
Popular with everybody:
<ul>
<loop:everybody1>
	<li><tag:everybody1[].Page />: <tag:everybody1[].Count /> hits</li>
</loop:everybody1>
</ul>
</div>

<div style="float: left; width: 350px;">
Popular with leaders:
<ul>
<loop:leaders1>
	<li><tag:leaders1[].Page />: <tag:leaders1[].Count /> hits</li>
</loop:leaders1>
</ul>
</div>

<div style="float: left; width: 350px;">
Popular with campers:
<ul>
<loop:campers1>
	<li><tag:campers1[].Page />: <tag:campers1[].Count /> hits</li>
</loop:campers1>
</ul>
</div>

<br clear="all">
<div style="float: left; width: 350px;">
Distinct viewers:
<ul>
<loop:everybody2>
	<li><tag:everybody2[].Page />: <tag:everybody2[].Count /> people</li>
</loop:everybody2>
</ul>
</div>

<div style="float: left; width: 350px;">
Distinct leaders:
<ul>
<loop:leaders2>
	<li><tag:leaders2[].Page />: <tag:leaders2[].Count /> leaders</li>
</loop:leaders2>
</ul>
</div>

<div style="float: left; width: 350px;">
Distinct campers:
<ul>
<loop:campers2>
	<li><tag:campers2[].Page />: <tag:campers2[].Count /> campers</li>
</loop:campers2>
</ul>
</div>

<br clear="all">
<div style="float: left; width: 350px;">
Favourite pages:
<ul>
<loop:everybody3>
	<li><tag:everybody3[].Name />: <tag:everybody3[].Page /> (<tag:everybody3[].Count /> hits)</li>
</loop:everybody3>
</ul>
</div>

<div style="float: left; width: 350px;">
Individual leaders:
<ul>
<loop:leaders3>
	<li><tag:leaders3[].Name />: <tag:leaders3[].Count /> hits</li>
</loop:leaders3>
</ul>
</div>

<div style="float: left; width: 350px;">
Individual campers:
<ul>
<loop:campers3>
	<li><tag:campers3[].Name />: <tag:campers3[].Count /> hits</li>
</loop:campers3>
</ul>
</div>
<else:leader>
You must be a leader to view this page.
</if:leader>
