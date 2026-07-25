output "catalog_name" {
  value = databricks_catalog.quantum_catalog.name
}

output "table_full_name" {
  value = "${databricks_catalog.quantum_catalog.name}.${databricks_schema.quantum_schema.name}.${databricks_sql_table.quantum_diffusion_events.name}"
}

output "zerobus_sp_application_id" {
  value = databricks_service_principal.zerobus_sp.application_id
}
