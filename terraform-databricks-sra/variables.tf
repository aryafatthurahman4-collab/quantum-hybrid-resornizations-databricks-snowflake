variable "databricks_host" {
  type        = string
  description = "Databricks Workspace URL (e.g. https://dbc-a1b2c3d4-e5f6.cloud.databricks.com)"
  default     = "https://dbc-a1b2c3d4-e5f6.cloud.databricks.com"
}

variable "catalog_name" {
  type        = string
  description = "Target Unity Catalog Name"
  default     = "quantum_catalog"
}
