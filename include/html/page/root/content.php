<?php
// load all projects
$projects = array();
foreach (list_projects() as $project_name)
	$projects[] = load_project($project_name);

// hide projects that don't match the selected tags, if any tags are selected
if (get_selected_tags())
	$projects = array_filter($projects,
		function($project) { return any_tags($project['tags']); });

// render a description of the project listing
generate_project_list_description($projects);

// render a preview box for each project
foreach ($projects as $project)
	generate_project_preview($project);
?>
