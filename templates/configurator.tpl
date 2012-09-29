<if:leader>

Your one-stop shop for configuring <tag:software />. Unlike the initial setup process, none of this information will be verified upon save. If things  break, you may have to edit <tt>config.json</tt> manually.<br /><br />

<ul class="tabs" data-tabs="tabs">
	<li class="active"><a href="#camp-information" class="error">General</a></li>
</ul>
 
<div class="tab-content">
	<div class="active" id="camp-information">

		<div class="row">
			<div class="span4">
			General settings that affect the whole site.
			</div>
			
			<div class="span12">
		
				<form name="camp-information">
					
					<div class="clearfix">
						<label>Boolean options:</label>
						<div class="input">
							<ul class="inputs-list">
								<li><label>
									<input type="checkbox" id="developerMode" name="developerMode" />
									<span>Developer mode</span>
								</label></li>
								<li><label>
									<input type="checkbox" id="pollCreation" name="pollCreation" />
									<span>Campers can create polls</span>
								</label></li>
								<li><label>
									<input type="checkbox" id="contactDetails" name="contactDetails" />
									<span>Campers can leave contact details</span>
								</label></li>
								<li><label>
									<input type="checkbox" id="showQueries" name="showQueries" />
									<span>Show MySQL queries on the bottom of each page</span>
								</label></li>
							</ul>
						</div>
					</div>
					
					<div class="clearfix">
						<label for="textarea">Suggestion categories</label>
						<div class="input">
						  <textarea rows="2" name="textarea2" id="textarea2" class="span4"></textarea>
						</div>
					</div>
					<div class="actions">
						<input class="btn" type="submit" value="Save settings" />
					</div>
					
				</form>
				
			</div>
					
		</div>
	
	</div>
	
</div>
		
<else:leader>
You must be a leader to view this page.
</if:leader>
