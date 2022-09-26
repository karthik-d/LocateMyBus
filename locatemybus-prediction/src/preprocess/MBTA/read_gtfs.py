import pandas as pd

# ROUTES = pd.read_csv('StaticGTFS/routes.txt')

TRIPS = pd.read_csv("StaticGTFS/trips.txt", dtype={'route_id':'string', 'trip_id':'string', 'direction_id':'int'})
# ['route_id', 'service_id', 'trip_id', 'trip_headsign', 'trip_short_name',
       # 'direction_id', 'block_id', 'shape_id', 'wheelchair_accessibl

STOP_TIMES = pd.read_csv('StaticGTFS/stop_times.txt', dtype={'trip_id':'string', 'stop_id':'string', 'stop_sequence':'int'})
# ['trip_id', 'arrival_time', 'departure_time', 'stop_id', 'stop_sequence',
       # 'stop_headsign', 'pickup_type', 'drop_off_type', 'timepoint',
       # 'checkpoint_id', 'continuous_pickup', 'continuous_drop_off']

STOPS = pd.read_csv("StaticGTFS/stops.txt", dtype={'stop_id':'string', 'stop_code':'string'})
# ['stop_id', 'stop_code', 'stop_name', 'stop_desc', 'platform_code',
       # 'platform_name', 'stop_lat', 'stop_lon', 'zone_id', 'stop_address',
       # 'stop_url', 'level_id', 'location_type', 'parent_station',
       # 'wheelchair_boarding', 'municipality', 'on_street', 'at_street',
       # 'vehicle_type']

def get_tripids_for_route(route_id, outbound=True):
    # outbound is 1, inbound is 2
    dir_id = int(outbound)
    return TRIPS.loc[(TRIPS['route_id']==route_id) & (TRIPS['direction_id']==dir_id), ['trip_id']]

def get_stopids_for_trip(trip_id):
    return STOP_TIMES.loc[STOP_TIMES['trip_id']==trip_id, ['stop_id', 'stop_sequence']].sort_values(by=['stop_sequence'])

def get_stop_details(stop_ids):
    return STOPS.loc[STOPS['stop_code'].isin(stop_ids), :]

def get_stops_for_trip(trip_id):
    stop_ids = get_stopids_for_trip(trip_id)
    stop_details = get_stop_details(stop_ids['stop_id'])
    # stop_code is a float
    return pd.merge(stop_ids,
                    stop_details,
                    left_on='stop_id',
                    right_on='stop_code')

def get_ideal_stopsequence_for_route():
    pass

trip_ids = get_tripids_for_route('1')['trip_id'].tolist()
print(trip_ids[100])
print(get_stops_for_trip(trip_ids[0]).loc[:, ['stop_sequence', 'stop_code', 'stop_name']])
