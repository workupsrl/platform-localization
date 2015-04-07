<script type="text/template" data-grid="locale" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
            <td><%= r.code %></td>
            <td><%= r.lang_code %></td>
            <td><%= r.name %></td>
            <td><%= r.display_name %></td>
            <td>
                <a class="btn btn-sm btn-default" href="<%= r.translations_uri %>">
                    {{{ trans('stevebauman/localization::locales/model.general.view_translations') }}}
                </a>
            </td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
