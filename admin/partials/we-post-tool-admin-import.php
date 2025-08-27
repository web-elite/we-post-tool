<?php

/**
 * WE Post Tool Admin Import Page
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('WE Post Tool - Import', 'we-post-tool'); ?></h1>
    <div id="we-post-tool-app" class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <!-- File Upload -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2"><?php _e('Upload Excel File', 'we-post-tool'); ?></label>
            <input type="file" id="excel-upload" accept=".xlsx,.xls" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>

        <!-- Column Mapping -->
        <div id="column-mapping" class="hidden mb-6">
            <h2 class="text-lg font-semibold mb-2"><?php _e('Map Columns to Taxonomies', 'we-post-tool'); ?></h2>
            <p class="mb-4"><?php _e('Please map the columns from your Excel file to the appropriate taxonomies below.', 'we-post-tool'); ?></p>
            <div id="mapping-container" class="space-y-4"></div>
        </div>

        <!-- Title Builder -->
        <div id="title-builder" class="mb-6">
            <h2 class="text-lg font-semibold mb-4"><?php _e('Build Post Title', 'we-post-tool'); ?></h2>
            <div id="variable-buttons" class="flex flex-wrap gap-2 mb-2"></div>
            <input id="title-template-input" type="text" placeholder="<?php _e('مثال: %city% - %state% ...', 'we-post-tool'); ?>" class="p-2 border rounded w-full">
            <div class="mt-4">
                <h3 class="text-sm font-medium"><?php _e('Preview', 'we-post-tool'); ?>:</h3>
                <p id="title-preview" class="text-gray-700"></p>
                <p id="permalink-preview" class="text-gray-500 italic"></p>
            </div>
        </div>

        <!-- Start Import -->
        <div id="import-controls" class="hidden">
            <button id="start-import" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"><?php _e('Start Import', 'we-post-tool'); ?></button>
        </div>

        <!-- Progress and Logs -->
        <div id="import-progress" class="hidden mt-6">
            <h2 class="text-lg font-semibold mb-4"><?php _e('Import Progress', 'we-post-tool'); ?></h2>
            <div id="progress-bar" class="w-full bg-gray-200 rounded-full h-4">
                <div id="progress-fill" class="bg-blue-600 h-4 rounded-full" style="width: 0%"></div>
            </div>
            <div id="log-container" class="mt-4 max-h-64 overflow-y-auto p-4 bg-gray-50 rounded"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        jQuery(document).ready(function($) {
            const uploadInput = document.getElementById('excel-upload');
            const mappingContainer = document.getElementById('mapping-container');
            const variableButtons = document.getElementById('variable-buttons');
            const titleTemplateInput = document.getElementById('title-template-input');
            const titlePreview = document.getElementById('title-preview');
            const permalinkPreview = document.getElementById('permalink-preview');
            const startImportBtn = document.getElementById('start-import');

            let excelData = [];
            let mappings = {};
            let taxonomyMeta = {}; // Store taxonomy base/rewrite info

            // File Upload
            uploadInput.addEventListener('change', async (e) => {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = async (event) => {
                    const data = new Uint8Array(event.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const sheet = workbook.Sheets[workbook.SheetNames[0]];
                    excelData = XLSX.utils.sheet_to_json(sheet, {
                        header: 1
                    });
                    renderColumnMapping(excelData[0]);
                    document.getElementById('column-mapping').classList.remove('hidden');
                    document.getElementById('title-builder').classList.remove('hidden');
                    renderVariableButtons();
                    updateTitlePreview();
                };
                reader.readAsArrayBuffer(file);
            });

            // Fetch taxonomies (now expects base and rewrite_slug)
            async function fetchTaxonomies() {
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'we_post_tool_get_taxonomies'
                    })
                });
                const taxonomies = await response.json();
                // Store meta for later use
                taxonomyMeta = {};
                if (Array.isArray(taxonomies)) {
                    taxonomies.forEach(tax => {
                        taxonomyMeta[tax.taxonomy] = {
                            base: tax.base || tax.taxonomy,
                            rewrite_slug: tax.rewrite_slug || tax.taxonomy
                        };
                    });
                }
                return taxonomies;
            }

            // Render column mapping
            async function renderColumnMapping(headers) {
                const taxonomies = await fetchTaxonomies();
                mappingContainer.innerHTML = '';
                headers.forEach((header, index) => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-4';
                    div.innerHTML = `
                        <label class="title text-sm font-medium" data-column="${index}">ستون ${index + 1} (${header.split('|')[0]})</label>
                        <select data-column="${index}" class="taxonomy-select p-2 border rounded">
                            <option value=""><?php _e('Select Taxonomy', 'we-post-tool'); ?></option>
                            ${Array.isArray(taxonomies) ? taxonomies.map(tax => `<option value="${tax.taxonomy}">${tax.name}</option>`).join('') : ''}
                        </select>
                        <label class="title text-sm font-medium" data-column="0"><?php _e('Parent Taxonomy:', 'we-post-tool'); ?></label>
                        <select class="parent-select p-2 border rounded hidden">
                            <option value=""><?php _e('No Parent', 'we-post-tool'); ?></option>
                        </select>
                    `;
                    mappingContainer.appendChild(div);
                });
            }

            // Render variable buttons for title builder
            function renderVariableButtons() {
                variableButtons.innerHTML = '';
                Object.keys(mappings).forEach(column => {
                    const taxonomy = mappings[column]?.taxonomy;
                    if (taxonomy) {
                        // نمایش نام ستون و تاکسونومی
                        const headerName = `ستون ${parseInt(column) + 1}`;
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'bg-gray-200 px-2 py-1 rounded hover:bg-blue-100 text-sm';
                        btn.textContent = `(%${taxonomy}_${column}%) (${headerName})`;
                        btn.onclick = () => {
                            insertAtCursor(titleTemplateInput, `(%${taxonomy}_${column}%)`);
                            titleTemplateInput.focus();
                            updateTitlePreview();
                        };
                        variableButtons.appendChild(btn);
                    }
                });
            }

            // Helper: insert text at cursor position
            function insertAtCursor(input, text) {
                const start = input.selectionStart;
                const end = input.selectionEnd;
                const value = input.value;
                input.value = value.substring(0, start) + text + value.substring(end);
                input.selectionStart = input.selectionEnd = start + text.length;
            }

            // Update title preview
            function updateTitlePreview() {
                let template = titleTemplateInput.value;

                // Reset previews
                titlePreview.textContent = template || '<?php _e('No Title', 'we-post-tool'); ?>';
                permalinkPreview.textContent = '<?php echo home_url(); ?>/post';

                // Process title template
                Object.keys(mappings).forEach(column => {
                    const taxonomy = mappings[column]?.taxonomy;
                    if (taxonomy && excelData[0][column]) {
                        let value = excelData[0][column].split('|')[0];
                        template = template.replaceAll(`(%${taxonomy}_${column}%)`, value);
                    }
                });

                titlePreview.textContent = template || '<?php _e('No Title', 'we-post-tool'); ?>';

                // Build permalinks for each taxonomy mapping (single link per taxonomy, full hierarchy)
                let permalinks = [];
                let processedTaxonomies = new Set();

                Object.keys(mappings).forEach(column => {
                    const mapping = mappings[column];
                    if (
                        mapping.taxonomy &&
                        excelData[0][column] &&
                        !processedTaxonomies.has(mapping.taxonomy)
                    ) {
                        const taxonomyInfo = taxonomyMeta[mapping.taxonomy] || {
                            base: mapping.taxonomy
                        };
                        let base = taxonomyInfo.base;

                        // Find the deepest child for this taxonomy
                        let deepestColumn = column;
                        // Traverse to the deepest child in this taxonomy
                        let foundDeeper = true;
                        while (foundDeeper) {
                            foundDeeper = false;
                            Object.keys(mappings).forEach(col => {
                                if (
                                    mappings[col].taxonomy === mapping.taxonomy &&
                                    mappings[col].parent_column === deepestColumn
                                ) {
                                    deepestColumn = col;
                                    foundDeeper = true;
                                }
                            });
                        }

                        // Build hierarchy chain from deepest child up to root
                        let chain = [];
                        let currentColumn = deepestColumn;
                        let visited = new Set();
                        while (currentColumn && !visited.has(currentColumn)) {
                            visited.add(currentColumn);
                            const value = excelData[0][currentColumn];
                            if (value) {
                                const parts = value.split('|');
                                const slug = parts.length > 1 ? parts[1] : parts[0].toLowerCase().replace(/\s+/g, '-');
                                chain.unshift(slug);
                            }
                            const parentCol = mappings[currentColumn]?.parent_column;
                            currentColumn = parentCol ? parentCol : null;
                        }

                        if (chain.length > 0) {
                            permalinks.push(`<?php echo home_url(); ?>/${base}/${chain.join('/')}/post`);
                        }
                        processedTaxonomies.add(mapping.taxonomy);
                    }
                });

                // Show all permalinks (one per taxonomy mapping)
                if (permalinks.length > 0) {
                    permalinkPreview.innerHTML = permalinks.map(link => `<div>${link}</div>`).join('');
                } else {
                    permalinkPreview.textContent = '<?php echo home_url(); ?>/post';
                }
            }

            // Update variable buttons and preview when mappings change
            mappingContainer.addEventListener('change', async (e) => {
                if (e.target.classList.contains('taxonomy-select')) {
                    const column = e.target.dataset.column;
                    const taxonomy = e.target.value;
                    mappings[column] = {
                        taxonomy,
                        parent_column: null
                    };

                    // Parent select logic (مثل قبل)
                    const sameTaxonomyTitles = [];
                    mappingContainer.querySelectorAll('div').forEach(div => {
                        const select = div.querySelector('.taxonomy-select');
                        if (select && select.value === taxonomy) {
                            const titleElem = div.querySelector('.title');
                            if (titleElem) {
                                sameTaxonomyTitles.push(titleElem.dataset.column);
                            }
                        }
                    });
                    mappingContainer.querySelectorAll('div').forEach(div => {
                        const select = div.querySelector('.taxonomy-select');
                        const parentSelect = div.querySelector('.parent-select');
                        if (select && select.value === taxonomy) {
                            const titleElem = div.querySelector('.title');
                            const currentTitle = titleElem ? titleElem.dataset.column : '';
                            const parentOptionsArr = sameTaxonomyTitles.filter(title => title !== currentTitle);
                            if (parentOptionsArr.length > 0) {
                                const parentOptions = parentOptionsArr
                                    .map(parent_column_id => `<option value="${parent_column_id}">${excelData[0][parent_column_id].split('|')[0]}</option>`)
                                    .join('');
                                parentSelect.innerHTML = `<option value=""><?php _e('No Parent', 'we-post-tool'); ?></option>` + parentOptions;
                                parentSelect.classList.remove('hidden');
                            } else {
                                parentSelect.innerHTML = `<option value=""><?php _e('No Parent', 'we-post-tool'); ?></option>`;
                                parentSelect.classList.remove('hidden');
                            }
                        }
                    });
                    document.getElementById('import-controls').classList.remove('hidden');
                }

                if (e.target.classList.contains('parent-select')) {
                    const parentSelect = e.target;
                    const column = parentSelect.closest('div').querySelector('.taxonomy-select').dataset.column;
                    const parentValue = parentSelect.value;
                    if (parentValue) {
                        mappings[column].parent_column = parentValue;
                    } else {
                        delete mappings[column].parent_column;
                    }
                }

                renderVariableButtons();
                updateTitlePreview();
            });

            // Update preview on input
            titleTemplateInput.addEventListener('input', updateTitlePreview);

            // Start import (بدون title-select)
            startImportBtn.addEventListener('click', async () => {
                document.getElementById('import-progress').classList.remove('hidden');
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'we_post_tool_start_import',
                        data: JSON.stringify(excelData),
                        mappings: JSON.stringify(mappings),
                        title: titleTemplateInput.value
                    })
                });
                const result = await response.json();
                document.getElementById('log-container').innerHTML = result.logs.map(log => `<p>${log}</p>`).join('');
                document.getElementById('progress-fill').style.width = '100%';
            });
        });
    });
</script>