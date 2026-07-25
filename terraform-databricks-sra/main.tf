# Databricks Security Reference Architecture (SRA) Terraform Module
# Deploys secure Databricks workspaces, Unity Catalog, and Zerobus Ingest resources.

terraform {
  required_version = ">= 1.5.0"
  required_providers {
    databricks = {
      source  = "databricks/databricks"
      version = "~> 1.30"
    }
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

provider "databricks" {
  host = var.databricks_host
}

# Unity Catalog for Quantum Hybrid Diffusion Models & Zerobus Ingestion
resource "databricks_catalog" "quantum_catalog" {
  name         = var.catalog_name
  comment      = "Unity Catalog for Quantum Hybrid Diffusion & Zerobus Ingestion Telemetry"
  properties = {
    purpose = "Quantum Machine Learning & Data Ingestion"
  }
}

resource "databricks_schema" "quantum_schema" {
  catalog_name = databricks_catalog.quantum_catalog.name
  name         = "default"
  comment      = "Default Schema for Quantum Telemetry Tables"
}

# Delta Lake Table Definition for Quantum Telemetry Events
resource "databricks_sql_table" "quantum_diffusion_events" {
  catalog_name       = databricks_catalog.quantum_catalog.name
  schema_name        = databricks_schema.quantum_schema.name
  name               = "quantum_diffusion_events"
  table_type         = "MANAGED"
  data_source_format = "DELTA"

  column {
    name = "offset"
    type = "BIGINT"
  }
  column {
    name = "timestamp_epoch_ms"
    type = "BIGINT"
  }
  column {
    name = "model_id"
    type = "STRING"
  }
  column {
    name = "qubits"
    type = "INT"
  }
  column {
    name = "fidelity"
    type = "DOUBLE"
  }
  column {
    name = "loss"
    type = "DOUBLE"
  }
  column {
    name = "engine"
    type = "STRING"
  }
}

# Service Principal for Zerobus Ingest OAuth Auth
resource "databricks_service_principal" "zerobus_sp" {
  display_name = "Zerobus-Ingest-Service-Principal"
}

# Grant Unity Catalog Privileges
resource "databricks_grants" "catalog_grants" {
  catalog = databricks_catalog.quantum_catalog.name
  grant {
    principal  = databricks_service_principal.zerobus_sp.application_id
    privileges = ["USE_CATALOG", "SELECT", "MODIFY"]
  }
}
