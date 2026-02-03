from pydantic_settings import BaseSettings, SettingsConfigDict


class Settings(BaseSettings):
    model_config = SettingsConfigDict(env_prefix="RBPING_", extra="ignore")

    # Agent -> server
    server_host: str = "127.0.0.1"
    server_port: int = 9274

    # Database (future)
    db_host: str = "127.0.0.1"
    db_port: int = 3306
    db_user: str = "rbping_master"
    db_pass: str = ""
    db_name: str = "rbping"
