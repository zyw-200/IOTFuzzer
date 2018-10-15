typedef struct 
{
  double handle_state_time;
  double handle_addr_time;
  double handle_syscall_time;
  double store_page_time;
  double restore_page_time;
  int user_syscall_count;
  int store_count;
} USER_MODE_TIME;

#define SNAPSHOT_SYNC
#define PRE_MAPPING
//#define PADDR_MAP_CHECK