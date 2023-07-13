# Workflow

``` mermaid
graph TD
  A["Files with metadata"] --> B{Upload Form};
  B --> C{"CADS Server"};
  C -->|"CSV/XLSX/etc. Files"| D{"datasets/"}
  C -->|"Metadata"| E{"metadata.json"}
  D -->|"XLSX files"| F{"To CSV"}
  D -->|"CSV files"| G[("Druid")]
  F --> G
  G --> H{"Data Catalog"}
  E --> H
  G --> I{"Druid Status"}
  G --> J{"Ontology"}
  H --> J
```