import {components} from "../schema";

type ResponseMetaData = components["schemas"]["meta"]

export type Get<ResponseResourceType> = {
  data: ResponseResourceType
}

export type Collection<ResponseResourceType> = Get<ResponseResourceType> & {
  meta: ResponseMetaData
}
