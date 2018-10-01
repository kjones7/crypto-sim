// Golden Layout testing
// $('body').html('');
$(document).ready( function () {
    const tableHTML = `
        <table id="table_id" class="display">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Row 1 Data 1</td>
                    <td>Row 1 Data 2</td>
                </tr>
                <tr>
                    <td>Row 2 Data 1</td>
                    <td>Row 2 Data 2</td>
                </tr>
            </tbody>
        </table>
    `;
    var config = {
        content: [{
            type: 'row',
            content:[{
                type: 'component',
                componentName: 'testComponent1',
                componentState: { label: 'A' }
            },{
                type: 'column',
                content:[{
                    type: 'component',
                    componentName: 'testComponent2',
                    componentState: { label: tableHTML }
                },{
                    type: 'component',
                    componentName: 'testComponent2',
                    componentState: { label: tableHTML }
                }]
            }]
        }]
    };
    var myLayout = new GoldenLayout( config );

    myLayout.registerComponent( 'testComponent1', function( container, componentState ){
        container.getElement().html( '<h5>' + componentState.label + '</h5>' );
    });
    myLayout.registerComponent( 'testComponent2', function( container, componentState ){
        container.getElement().html( tableHTML );
    });

    myLayout.init();

    $('.display').DataTable();
} );
