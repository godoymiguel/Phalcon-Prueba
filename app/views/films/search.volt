{{ content() }}

<ul class="pager">
    <li class="previous">
        {{ link_to("films", "&larr; Go Back") }}
    </li>
    <li class="next">
        {{ link_to("films/new", "Create films") }}
    </li>
</ul>

{% for film in page.items %}
    {% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Director</th>
            <th>Name</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
    {% endif %}
        <tr>
            <td>{{ film.id }}</td>
            <td>{{ film.getDirectors().name }}</td>
            <td>{{ film.name }}</td>
            <td>{{ film.description }}</td>
            <td width="7%">{{ link_to("films/edit/" ~ film.id, '<i class="glyphicon glyphicon-edit"></i> Edit', "class": "btn btn-default") }}</td>
            <td width="7%">{{ link_to("films/delete/" ~ film.id, '<i class="glyphicon glyphicon-remove"></i> Delete', "class": "btn btn-default") }}</td>
        </tr>
    {% if loop.last %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="7" align="right">
                <div class="btn-group">
                    {{ link_to("films/search", '<i class="icon-fast-backward"></i> First', "class": "btn") }}
                    {{ link_to("films/search?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn") }}
                    {{ link_to("films/search?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn") }}
                    {{ link_to("films/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn") }}
                    <span class="help-inline">{{ page.current }} of {{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
    </tbody>
</table>
    {% endif %}
{% else %}
    No films are recorded
{% endfor %}
